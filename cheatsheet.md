# Cheatsheet CodeIgniter 4 — Sessions & structure du projet

## 1. Structure des fichiers à toucher pour une fonctionnalité de session (login client)

```
app/
├── Config/
│   ├── Routes.php          ← déclarer les routes (login, logout, dashboard...)
│   └── Filters.php         ← déclarer le filtre d'authentification
├── Controllers/
│   └── Auth.php            ← logique login/logout (côté client)
├── Models/
│   └── ClientModel.php     ← requêtes SQL vers la table `clients`
├── Views/
│   ├── auth/
│   │   └── login.php       ← formulaire numéro de téléphone
│   └── client/
│       └── dashboard.php   ← page après connexion
└── Filters/
    └── AuthFilter.php      ← vérifie que la session existe avant d'accéder aux pages protégées
```

---

## 2. La classe Session en CI4

CI4 a une librairie Session intégrée, pas besoin de l'installer.

### Récupérer l'instance session
```php
$session = session(); // helper global, dispo partout (controllers, views)
```

### Stocker des données
```php
session()->set('client_id', $client->id);
session()->set('numero_telephone', $client->numero_telephone);

// ou plusieurs valeurs d'un coup
session()->set([
    'client_id'        => $client->id,
    'numero_telephone' => $client->numero_telephone,
    'isLoggedIn'        => true,
]);
```

### Lire des données
```php
$id = session()->get('client_id');
$all = session()->get(); // tout le tableau de session
```

### Vérifier l'existence
```php
if (session()->has('client_id')) {
    // connecté
}
```

### Supprimer / déconnexion
```php
session()->remove('client_id');   // supprime une seule clé
session()->destroy();             // détruit toute la session (logout complet)
```

### Flashdata (message qui ne survit qu'à la prochaine requête — utile pour "numéro invalide")
```php
session()->setFlashdata('error', 'Numéro de téléphone invalide');
// dans la vue :
echo session()->getFlashdata('error');
```

---

## 3. Exemple concret — `app/Controllers/Auth.php`

```php
<?php

namespace App\Controllers;

use App\Models\ClientModel;

class Auth extends BaseController
{
    public function loginForm()
    {
        return view('auth/login');
    }

    public function login()
    {
        $numero = $this->request->getPost('numero_telephone');

        $clientModel = new ClientModel();
        $client = $clientModel->where('numero_telephone', $numero)->first();

        if (!$client) {
            // login automatique = pas d'inscription préalable
            // donc on peut soit refuser, soit créer le compte à la volée
            session()->setFlashdata('error', 'Numéro non reconnu');
            return redirect()->to('/login');
        }

        session()->set([
            'client_id'        => $client['id'],
            'numero_telephone' => $client['numero_telephone'],
            'isLoggedIn'        => true,
        ]);

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
```

---

## 4. `app/Config/Routes.php`

```php
$routes->get('/login', 'Auth::loginForm');
$routes->post('/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');

// Routes protégées (groupées avec le filtre auth)
$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('/dashboard', 'Client::dashboard');
    $routes->get('/solde', 'Client::solde');
    $routes->post('/depot', 'Client::depot');
    $routes->post('/retrait', 'Client::retrait');
    $routes->post('/transfert', 'Client::transfert');
    $routes->get('/historique', 'Client::historique');
});
```

---

## 5. Filtre d'authentification — `app/Filters/AuthFilter.php`

```php
<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // rien à faire ici
    }
}
```

### Déclarer le filtre — `app/Config/Filters.php`
```php
public array $aliases = [
    // ... filtres existants
    'auth' => \App\Filters\AuthFilter::class,
];
```

---

## 6. Config session (optionnel) — `app/Config/Session.php`

```php
public string $driver = FileHandler::class; // ou DatabaseHandler::class
public string $cookieName = 'ci_session';
public int $expiration = 7200; // 2h en secondes
public string $savePath = WRITEPATH . 'session';
public bool $matchIP = false;
public int $timeToUpdate = 300;
public bool $regenerateDestroy = false;
```

⚠️ Le dossier `writable/session/` doit exister et être accessible en écriture (comme `writable/database/`).

---

## 7. Commandes `spark` utiles (CLI CI4)

```bash
php spark serve                          # lance le serveur local (http://localhost:8080)
php spark routes                         # liste toutes les routes déclarées
php spark make:controller Auth           # crée app/Controllers/Auth.php
php spark make:model ClientModel         # crée app/Models/ClientModel.php
php spark make:filter AuthFilter         # crée app/Filters/AuthFilter.php
php spark make:migration CreateTables    # crée une migration
php spark migrate                        # exécute les migrations
php spark migrate:rollback               # annule la dernière migration
php spark db:table clients               # affiche la structure + contenu d'une table
```

---

## 8. Modèle type — `app/Models/ClientModel.php`

```php
<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table            = 'clients';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['numero_telephone', 'solde'];
    protected $useTimestamps    = false;
    protected $returnType       = 'array';
}
```

---

## 9. Pense-bête pour le travail en binôme (éviter les conflits)

| Fichier | Qui le touche |
|---|---|
| `Routes.php` | Les deux — ajouter ses routes **à la fin de sa section**, ne pas réécrire les routes de l'autre |
| `Filters.php` | Un seul binôme, une fois configuré ça bouge peu |
| `Controllers/Auth.php` | Sanda (côté client) |
| `Controllers/Operateur.php` | Olivier (côté opérateur) |
| `Models/` | Un modèle = un fichier = pas de conflit si chacun crée les siens |
| `Views/` | Dossiers séparés (`auth/`, `client/`, `operateur/`) |