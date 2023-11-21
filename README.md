<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## CLONARE UNA REPO DA GITHUB

CREARE LA NUOVA REPO
COPIARE IL LINK DAL DROPDOWN ***CODE*** DA GitHub
ESEGUIRE
```bash
git clone [LINK CLONAZIONE COPIATO] [NOME DIRECTORY DI DESTINAZIONE]
```
ES:
```bash
git clone https://github.com/francescomascellino/laravel-many-to-many.git laravel-api
```

ELIMINARE LA CARTELLA .git
```bash
rm -rf .git
```

INIZIALIZZARE E COMMITTARE
```bash
git init
git add .
git commit -m "commit message"
```

COPIARE I COMANDI DI DA GITHUB PER AGGIUNGERE L'ORIGINE REMOTA E PUSHARE SU MAIN
```bash
git remote add origin https://github.com/francescomascellino/laravel-api.git
git branch -M main
git push -u origin main
```

INSTALLARE LE DIPENDENZE
```bash
composer install
```

RINOMINARE IL FILE COPIA DI .env ED EFFETTUARE LE MODIFICHE

(DATI DEL DATABASE, NOME APP, DISK SU public...)

GENERARE LA CHIAVE DELL'APP
```bash
php artisan key:generate
```

COLLEGARE LO STORAGE
```bash
php artisan storage:link
```

INSTALLARE I PACCHETTI
```bash
npm i
```

SE NECESSARIO CREARE LA CARTELLA DI SALVATAGGIO DEI FILES IN storage/app/public/[image disk folder]

MIGRARE LE VECCHIE TABELLE
```bash
php artisan migrate
```

PER SEEDARE LE VECCHIE TABELLE
```bash
php artisan db:seed --class=TypeSeeder

php artisan db:seed --class=ProjectSeeder
```

## API

```php
// RETURNS ARRAY
Route::get('projects', function () {
    return Project::all()
    ]);
});
```

AGGIUNGERE LA ROTTA DELL'API IN ***routes/api.php***
```php
// RETURNS JSON
Route::get('projects', function () {
    return response()->json([
        'status' => 'success',
        'result' => Project::all()
    ]);
});
```
***paginate()*** (VUOTO = 15)
```php
// RETURNS JSON
Route::get('projects', function () {
    return response()->json([
        'status' => 'success',
        // PAGINAZIONE
        'result' => Project::paginate(5)
    ]);
});
```
ADD EAGER LOADING PER VISUALIZZARE LE RELATIONS
```php
// RETURNS JSON
Route::get('projects', function () {
    $projects = Project::with('type', 'technologies')->paginate(5);
    return response()->json([
        'status' => 'success',
        'result' => $projects
    ]);
});
```

ORDINE DISCENDENTE
```php
Route::get('projects', function () {
    $projects = Project::with('type', 'technologies')->OrderbyDesc('id')->paginate(5);
    return response()->json([
        'status' => 'success',
        'result' => $projects
    ]);
});
```

## CORS CROSS ORIGIN RESOURCE SHARIG

IN ***config/cors.php***
FA SI CHE TUTTE LE ORIGINI POSSANO CONSUMARE I DATI
```php
'allowed_origins' => ['*'],
```

POSSIAMO INSERIRE DIRETTAMENTE NELL'ARRAY L'URL O AGGIUNGERE NEL FILE ***.env*** UNA VARIABLE:
```bash
APP_FRONTEND_URL=http://localhost:5174
```

E INSERIRLA NEL VALORE DELLA CHIAVE:

```php
'allowed_origins' => ['APP_FRONTEND_URL', 'http://localhost:5174'], // IL SECONDO E' UN VALORE DI DEFAULT
```

OTTENERE GLI ULTIMI TRE PROJECTS
```php
Route::get('projects/latest', function () {
    $latest = Project::with('type', 'technologies')->OrderbyDesc('id')->take(3)->get();
    return response()->json([
        'status' => 'success',
        'result' => $latest
    ]);
});
```

OTTENERE SINGOLO PROGETTO TRAMITE SLUG
```php
Route::get('projects/{project:slug}', function ($slug) {

    $project = Project::with('type', 'technologies')->where('slug', $slug)->first();

    if ($project) {
        return response()->json([
        'success' => true,
        'result' => $project 
        ]);
    } else {
        return response()->json([
        'success' => false,
        'result' => 'Project not found' 
        ]);
    }
});
```

CREARE CONTROLLERS E MODELLI PER API/ProjectController, API/TypeController, API/TechnologyController DOVE INSERIRE LE ROTTE

ES: ***API/TechnologyController***:
```php
public function index()
    {
        $technologies = Technology::OrderBy('name')->get();
        return response()->json([
            'status' => 'success',
            'result' => $technologies
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $technology = Technology::with('project', 'project.technologies', 'project.type')->where('slug', $slug)->first();

        if ($technology) {
            return response()->json([
                'success' => true,
                'result' => $technology
            ]);
        } else {
            return response()->json([
                'success' => false,
                'result' => 'Technology not found'
            ]);
        }
    }
```

AGGIORNARE ***api.php***
```php
Route::get('technologies', [TechnologyController::class, 'index']);
Route::get('technologies/{technology:slug}', [TechnologyController::class, 'show']);
```
