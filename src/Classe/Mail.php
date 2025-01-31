<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

Class Mail
{
    // une fonction pour gérer les parameters email 
public function send($to_email, $to_name, $subject, $template, $vars = null)
    {
        // Récupération du contenu du template
        $content = file_get_contents(dirname(__DIR__) . '/Email/' . $template);
        
        // Récupération des variables facultatives
        if ($vars){
            foreach ($vars as $key=>$var) {
                $content = str_replace('{'. $key . '}', $var, $content);
            }    
        }

            // Création d'un client Mailjet
            $mj  = new Client($_ENV['MJ_APIKEY_PUBLIC'], $_ENV['MJ_APIKEY_PRIVATE'], true, ['version' => 'v3.1']);

            // Défenir notre request des messages
            $body = [
                'Messages' => [
                    [
                        'From' => [
                            'Email' => "secuproshop@gmail.com",
                            'Name' => "SecuProShop"
                        ],
                        'To' => [
                            [
                                'Email' => $to_email,
                                'Name' => $to_name
                            ]
                        ],
                        'TemplateID' => 6676924,
                        'TemplateLanguage' => true,
                        'Subject' => $subject,
                        'Variables' => [
                            'content' => $content
                        ]
                    ]
                ]
            ];

            // Envoi de la requête
            $mj->post(Resources::$Email, ['body' => $body]);
    }
}