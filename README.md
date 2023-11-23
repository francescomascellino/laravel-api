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
```bash
php artisan make:controller API/TechnologyController --resource --model=Technology
```

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

## LARAVEL MAILABLES (EMAIL)

CREARE MODELLO DOVE SALVARE DATI EMAIL
```bash
php artisan make:model Lead -m
```

Lead MODEL
```php
protected fillable campi form frontend
```

Lead MIGRATION
```php
protected $fillable = ['name', 'email', 'phone', 'message'];
```

```bash
php artisan make:mail NewLeadEmail
```

REGISTRARSI SU mailtrap.io, SELEZIONARE MAILBOX E SMTP DI LARAVEL. COPIARE I DATI NEL FILE .env E COMPLETARE I CAMPI RICHIESTI
```php
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME==***********
MAIL_PASSWORD=***********
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@portfolio.com" // APP EMAIL
MAIL_FROM_NAME="${APP_NAME}"
```

CREARE VIEW DI MARKUP IN viewv/mail/

OPPURE CREARE UNA CLASSE CON MARKDOWN CHE CREERA' DIRETTAMENTE IL FILE E LA CARTELLA
```bash
php artisan make:mail FromLeadEmail --markdown
```

SU ***FromLeadEmail***:

https://laravel.com/docs/10.x/mail#configuring-the-sender
```php
// VARIABILE CONTENENTE ISTANZA DEL MODELLO Lead
public $lead;

    /**
     * Create a new message instance.
     */
    public function __construct($lead)
    {
        $this->lead = $lead;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@portfolio.com', 'Portfolio'), // INDIRIZZO NOREPLY. E' IL FROM DI mailtrap.io
            replyTo: '',
            subject: 'New Lead Email',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.new-lead-email-md',
        );
    }
```

CRARE CONTROLLER CHE RICEVE I DATI DEL FORM FRONT, LI SALVA IN DB E INVIA LA MAIL
```bash
php artisan make:controller API/LeadController --model=Lead
```

SU ***LeadController***:
https://laravel.com/docs/10.x/validation#manually-creating-validators
```php
public function store(Request $request)
{
    // VALIDATE
    $validator = Validator::make($request->all(), [
        'name' => 'required|mx:100'
        'email' => 'required|email',
        'phone' => 'required',
        'message' => 'required',
    ])

    // SE LA VALIDAZIONE FALLISCE...
    if ($validator->fails()) {
            return response()->json([
                'sussess' => false,
                'errors' => $validator->errors()
            ]);
        }

    // SE LA VALIDAZIONE RIESCE, SALVA NEL DATABASE UNA ISTANZA DI Lead CONTENENTE I DATI DELLA Request
    $lead = Lead::create($request->all());

    // INVIA UNA MAIL ALL'ADMIN
    Mail::to('admin@mailtrap.io')->send(new FromLeadEmail($lead)); // MY EMAIL ADRESS, DOVE VENGONO REINDIRIZZATE LE MAIL INVIATE DALL'APP

    // INVIA UNA COPIA AL MITTENTE
    Mail::to($lead->email)->send(new ToLeadEmail($lead)); // O ALTRO MODELLO DA CREARE APPOSITAMENTE

    // RITORNA AL FRONTEND UN JSON CON UN ESITO POSITIVO
    return response()->json(
        [
        'success' => true,
        'message' => 'mail sent'
        ]
    );

}
```

api.php
```php
Route::post('/lead', [LeadController::class, 'store']);
```

LATO FRON VIEW DI CONTATTO


DATA
```js
data() {
        return {
            store,

            // NEL SUBBMIT :disabled="loading" O PER MOSTRARE UN LOADER:
            // <span v-if="loading">Sending <i class="fa-solid fa-circle-notch fa-spin"></i></span>
            loading: false,

            name: '',
            email: '',
            phone: '',
            message: '',
            errors: [],

        }
    },
```
IN OGNI CAMPO DEL FORM AGGIUINGERE v-model PER OGNO CAMPO DEL FORM
ES: v-model="name"

EDITARE IL TAG form
```html
<form action="" v-on:submit.prevent="sendForm()">
```

METHODS:
```js
methods: {
    sendForm() {
        
        // SVUOTA ERRORI E RISULTATI
            this.errors = [];

            this.success = null;

            // ASSEGNA A PAYLOAD I DATI DEL FORM
            const payload = {
                name: this.name,
                email: this.email,
                phone: this.phone,
                message: this.message,
            };

        axios.post(this.store.baseUrl + 'api/lead/', payload)
                .then(response =>{

            // ASSEGNA A SUCCESS IL VALORE DI data.success. SE CI SONO callWithErrorHandling, QUESTA SARA' UNDEFINED ED ENTRERA' NELL'IF
            const success = response.data.success;

            if(!success) {
                console.log('Errors:', response.data.errors);

                // ASSEGNA A this.errors IL VALORE DELLA RESPONSE errors IN MODO DA POTERLI USARE IN PAGINA
                this.errors = response.data.errors;

                // POSSONO ESSERES STAMPATI IN PAGINA -> campo del form :class="{ 'is-invalid': errors.name }" SOTTO IL FORM un alert v-if="errors.nomecampo" con una ul>li v-for="message in errors.message" CON CONTENUTO {{ message }}

            } else {
                console.log('VALIDATION PASSED:', response);

                // SVUOTA I CAMPI
                this.name = '';
                this.email = '';
                this.phone = '';
                this.message = '';
                this.phone = '';

                this.success = response.data.message;, // -> in pagina <span v-if="success">{{ success }}</span>  
            }

            this.loading = false

        })
        .catch(err => {
        console.error(err);
        })
    }
}
```