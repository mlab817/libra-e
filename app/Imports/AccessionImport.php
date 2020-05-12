<?php

namespace App\Imports;

use App\Accession;
use App\NoAccession;
use App\ConflictAccession;
use App\Author;
use App\Publisher;

use App\AquisitionBook;

use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class AccessionImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $first = 0;

        foreach ($rows as $row) 
        {
            $first++;

            if($first != 1){

                $accession_no = $row[0];
                
                $accession_exist = NoAccession::where('accession_no', $accession_no)->exists();
                
                if($accession_exist){
                    
                    $accession_id = NoAccession::where('accession_no', $accession_no)->select('accession_id')->first();
    
                    $id = $this->search_accession($row[2], $row[1]);
                    
                    if($id != 0 || $id != $accession_id['id']){
    
                        $this->add_conflict_excell($accession_no, $row[1], $row[2], $row[3]);
                        
                    }
                    
                }else{
                    
                    $id = $this->search_accession($row[2], $row[1]);
                    
                    if($id == 0){
        
                        $author_id = $this->store_author($row[1]);
                        $publisher_id = $this->store_publisher($row[3]);
                        
                        $accession = Accession::create([
                            'author_id' => $author_id,
                            'book_title' => $row[2],
                            'publisher_id' => $publisher_id,
                            'copyright' => (int)$row[4],
                        ]);
                            
                        $inserted_id = $accession->id;
                            
                        NoAccession::create([
                            'accession_no' => $accession_no,
                            'accession_id' => $inserted_id,
                            'status' => 1,
                        ]);

                        AquisitionBook::create([
                            'acc_id' => $inserted_id,
                            'quantity' => 1,
                            'aquisition_type' => 1,
                        ]);
                        
                    }else{
                        
                        NoAccession::create([
                            'accession_no' => $accession_no,
                            'accession_id' => $id,
                            'status' => 1,
                            ]);
                            
                        AquisitionBook::create([
                            'acc_id' => $id,
                            'quantity' => 1,
                            'aquisition_type' => 1,
                        ]);
                    }
                }
            }
        }
    }

    public function search_accession($accession_title, $author_name)
    {
        $author_exists = Author::where('author_name', $author_name)->exists();
        
        if($author_exists){
            
            $author_id = Author::where('author_name', $author_name)->select('id')->get();

            $accession_exists = Accession::where([
                ['author_id',  $author_id[0]->id],
                ['book_title', $accession_title]
            ]);
            
            if($accession_exists->count() > 0){
                
                $accession_id = $accession_exists->select('id')->first();

                return $accession_id['id'];

            }else{

                return 0;

            }

        }else{
            
            return 0;
            
        }
    }

    public function store_author($author_excell)
    {
        $author_name =  ltrim(ucfirst($author_excell));

        if($author_name == ''){
            $author_name = "Unknown Author";
        }

        $exists = Author::where('author_name', $author_name)->exists();
        
        if($exists){
            
            $author_id = Author::where('author_name', $author_name)->select('id')->first();
            return $author_id['id'];
            
        }else{
            
            $author = new Author;

            $author->author_name =  $author_name;

            $author->status = 1;
            
            $author->save();

            $inserted_id = $author->id;

            return $inserted_id ;

        }
    }
    
    public function store_publisher($publisher_excell)
    {
        $publisher_name =  ltrim(ucfirst($publisher_excell));

        if($publisher_name == ''){
            $publisher_name = "Unknown Publisher";
        }
        
        $exists = Publisher::where('name', $publisher_name)->exists();

        if($exists){
            
            $publisher_id = Publisher::where('name', $publisher_name)->select('id')->first();
            return $publisher_id['id'];
            
        }

        $publisher = new Publisher;

        $publisher->name =  $publisher_name;
        
        $publisher->status = 1;
        
        $publisher->save();

        $inserted_id = $publisher->id;

        return $inserted_id;
        
    }

    public function add_conflict_excell($accession_no, $author, $title, $publisher)
    {
        ConflictAccession::create([
            'accession_no' => $accession_no,
            'author' => $author,
            'title' => $title,
            'publisher' => $publisher,
            'status' => 1,
        ]);
    }

    public function to_string_accession_no($accession_no)
    {
        $num = $accession_no;
        $num = (string)$num;
        $num_length = strlen($num);


        for($i = $num_length; $i < 6; $i++){
        $num = "0" . $num;
        }

        return $num;

    }
    
}
