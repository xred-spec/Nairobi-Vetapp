<?php

namespace App;

use Illuminate\Support\Facades\Http;

class MailjetClass
{
    /**
     * Create a new class instance.
     */
    protected $apiKey;
    protected $apiSecret;
    protected $baseEmail;
    protected $baseName;
    protected $body = [];
    protected $to =[];
    protected $subject="";
    protected $HTMLPart="";
    protected $sender = [];
    protected $baseUrl = 'https://api.brevo.com/v3/smtp/email';
    public function __construct(String $apiKey, String $baseEmail,String $baseName)
    {
        $this->apiKey = $apiKey;
       // $this->apiSecret = $apiSecret;
        $this->baseEmail = $baseEmail;
        $this->baseName = $baseName;
    }
    
    public function from(){
        $this->sender = [
            'email'=>$this->baseEmail,
            'name'=>$this->baseName
        ];
        return $this;
    }

    public function to($recepetors){
        $this->to[]=$recepetors;
        return $this;
    }
    public function subject(String $topic){
        $this->subject=$topic;
        return $this;
    }

    public function htmlContent(String $content){
        $this->HTMLPart=$content;
        return $this;
    }

    public function send(){
        return Http::retry(3, 300)->withHeaders(['api-key'=>$this->apiKey])->post($this->baseUrl,[
           "sender"=>$this->sender,
           "to"=>$this->to,
           'subject'=>$this->subject,
           "htmlContent"=>$this->HTMLPart
        ]);
    }

}