<?PHP 

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart 
{
    // le constructeur nous permet d'accéder à la session Symfony
    public function __construct(private RequestStack $requestStack)
    {

    }

    // la fonction nous permet d'ajouter un produit au panier
    public function add($product)
    {
        // 1.appeler la session de symfony
        $cart = $this->requestStack->getSession()->get('cart'); //récupérer le panier de la session ou initialiser un tableau vide s'il n'existe pas
        // 2.Ajouter le produit au panier
        if (isset($cart[$product->getId()])){
            $cart[$product->getId()] = [
                'object' => $product,
                'quantity' => $cart[$product->getId()]['quantity'] + 1 //si le produit existe déjà dans le panier, on augmente la quantité
            ];
        } else {
            $cart[$product->getId()] = [
                'object' => $product,
                'quantity' => 1 //si le produit existe déjà dans le panier, on augmente la quantité
            ];
        }

             //tableau associatif pour stocker les produits dans le panier
        
        // 3.Créer une variable session(mettre a jour le panier)
        $this->requestStack->getSession()->set('cart', $cart);
    }

    // la fonction nous permet de diminuer la quantité d'un produit dans le panier
    public function decrease($id)
    {
        $cart = $this->requestStack->getSession()->get('cart');
        if($cart[$id]['quantity']>1){
            $cart[$id]['quantity'] = $cart[$id]['quantity'] - 1;
        }else{
            unset($cart[$id]);
        }
        $this->requestStack->getSession()->set('cart', $cart); //mettre a jour le panier
    }

    // la fonction nous permet de calculer la quantité totale du panier
    public function fullQuantity()
    {
        $cart = $this->requestStack->getSession()->get('cart');
        $qty = 0;

        if(!isset($cart)){
            return $qty;
        }

        foreach ($cart as $product) {
            $qty += $product['quantity'];
        }
        return $qty;
    }

    // la fonction nous permet de calculer le prix total du panier
    public function getTotalWt()
    {
        $cart = $this->requestStack->getSession()->get('cart');
        $price = 0;

        if(!isset($cart)){
            return $price;
        }
        
        foreach ($cart as $product) {
            $price = $price + ($product['object']->getPriceWt() * $product['quantity']);
        }
        return $price;
    }

    // la fonction nous permet de supprimer le panier
    public function remove()
    {
        return $this->requestStack->getSession()->remove('cart');
    }

    // la fonction nous permet de récupérer le panier
    public function getCart()
    {
        return $this->requestStack->getSession()->get('cart');
    }
}