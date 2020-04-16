<?php
namespace app\vendor\graphql;

class Yze_Graphql_Parser{
    private $queryString;
    private $hasParsed = false;
    private $acts = [];
    
    public function __construct($queryString) {
        $this->queryString = $queryString;
    }
    
    public function getFields(){
        if( ! $this->hasParsed){
            $this->parse();
        }
    }
    
    public function getActs(){
        if( ! $this->hasParsed){
            $this->parse();
        }
        return $this->acts;
    }
    
    private function parse(){
        $index = 0;
        $maxLength = mb_strlen($this->queryString, "UTF-8");
        
        $literal = "";
        while( $index < $maxLength){
            $char = trim($this->queryString[ $index++ ]);
            if( ! $char ){
                $literal .= $char;
                continue;
            }
            
            if( in_array( $char, ["{","}","(",")",","] )){
                
                if($literal){
                    $this->acts[] = [
                        "literal" => $literal
                    ];
                }
                
                $this->acts[] = [
                    "literal" => $char
                ];
                
                
                $literal = "";
                continue;
            }
            
            $literal .= $char;
        }
        
        $this->hasParsed = true;
    }
}