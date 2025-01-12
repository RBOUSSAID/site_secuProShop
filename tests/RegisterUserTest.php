<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {
        //Créer un client (navigateur web) de pointer vers un URL 
        $client = static::createClient();
        $client->request('GET', '/inscription');

        //remplir un formulaire d'inscription
        $client->submitForm('S\'inscrire', [
            'register_user[email]' => 'test@example.com',
            'register_user[plainPassword][first]' => '12345678',
            'register_user[plainPassword][second]' => '12345678',
            'register_user[firstname]' => 'Rabah',
            'register_user[lastname]' => 'rabah'

        ]);

        //Follower(suivre les redirections) le lien de confirmation
        $this->assertResponseRedirects('/connexion'); //test redirect(tester la redirection du confirmation) 
        $client->followRedirect();

        //vérifier que le formulaire est bien rempli et valide (message d'alerte de champs requis)
        $this->assertSelectorExists('div:contains("Votre compte a bien été créée. Vous pouvez vous connecter maintenant.")');   
    }
}
