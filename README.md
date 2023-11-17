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
git clone https://github.com/francescomascellino/laravel-one-to-many.git laravel-many-to-many
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
git remote add origin https://github.com/francescomascellino/laravel-many-to-many.git
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

## MANY TO MANY RELATIONSHIP

CREARE MODELLO, MIGRATION E SEEDER
```bash
php artisan make:model Technology -ms // migration seeder
```

EDITARE LA MIGRATION DELLA NUOVA TABELLA ***technologies***
```php
public function up(): void
    {
        Schema::create('technologies', function (Blueprint $table) {
            $table->id();

            $table->string('name', 50)->unique;
            $table->string('slug', 50);

            $table->timestamps();
        });
    }
```

EDITARE IL SEEDER DELLA NUOVA TABELLA INSERENDO LE PRIME TECNOLOGIE
```php
public function run(): void
    {
        $technologies = ['CSS', 'Html', 'Javascript', 'Bootstrap', 'Vue.js', 'Vite', 'Php', 'MySQL', 'Laravel'];

        foreach ($technologies as $technology) {
            $newTechnology = new Technology();
            $newTechnology->name = $technology;
            $newTechnology->slug = Str::slug($newTechnology->name);
            $newTechnology->save();
        }

    }
```

ESEGUIRE LA MIGRAZIONE
```bash
php artisan db:seed --class=TechnologySeeder
```

CREARE LA MIGRAZIONE PER LA TABELLA PIVOT
❗**ATTENZIONE USARE L'ORDINE ALFABETICO NELLE TABELLE DA COLLEGARE**❗
```bash
php artisan make:migration create_project_technology_table
```

EDITARE LA MIGRATION DELLA NUOVA TABELLA ***project_technology***
```php
public function up(): void
    {
        Schema::create('project_technology', function (Blueprint $table) {
            // $table->id();

            $table->primary(['project_id', 'technology_id']); // IMPEDISCE CHE SI POSSA ASSOCIARE DUE VOLTE LO STESSO PROGETTO E LA STESSA TECH (QUINDI EVITANDO PROGETTI CON DUE TECH UGUALI)

            $table->unsignedBigInteger('project_id'); // CREA LA COLONNA
            $table->foreign('project_id') // ASSEGNA LA COLONNA ALL'ID DI projects
                ->references('id')
                ->on('projects');

            $table->unsignedBigInteger('technology_id'); // CREA LA COLONNA
            $table->foreign('technology_id') // ASSEGNA LA COLONNA ALL'ID DI technologies
                ->references('id')
                ->on('technologies');

            $table->timestamps();
        });
    }

```

AGGIUNGERE LE RELAZIONI NEL MODELLO ***Project***
```php
public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class);
    }
```
AGGIUNGERE LE RELAZIONI NEL MODELLO ***Technology***
```php
public function project(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }
```

ESEGUIRE LA MIGRATION

```bash
php artisan migrate
```

AGGIORNARE IL METODO ***create()*** NEL ***ProjectController***
```php
public function create()
    {
        $page_title = 'Add New';
        $types = Type::all();
        $technologies = Technology::all();
        return view('admin.projects.create', compact('page_title', 'types', 'technologies'));
    }
```

AGGIUNGERE IL CAPO DI SELEZIONE DELLE TECHNOLOGIES NELLA VUSTA ***admin.projects.create***
```php
<div class="mb-3">

    <label for="technologies" class="form-label"><strong>Technologies Used</strong></label>

        // VIENE DATO UN ARRAY COME NAME PER ACCETTARE SCELTE MULTIPLE
        <select class="form-select" multiple name="technologies[]" id="technologies">

            <option disabled>Select Technologies used</option>

            @foreach ($technologies as $technology)
                <option value="{{ $technology->id }}"
                    // CONFRONTA L'ARRAY DEGLI ID DELLE TECHNOLOGIES CON QUELLO CONTENENTE I CAMPI SELEZIONATI PRECEDENTEMENTE
                    // SE VI SONO CORRISPONDENZE LI PRESELEZIONA
                    // SE L'ARRAY OLD NON ESISTE CONFRONTA UN ARRAY VUOTO [] COME FALLBACK, AUTOMATICAMENTE NON TROVANDO CORRISPONDENZE E NON SELEZIONANDO NULLA 
                    {{ in_array($technology->id, old('technologies', [])) ? 'selected' : '' }}>

                {{ $technology->name }} ID: {{ $technology->id }}</option>

            @endforeach

        </select>

        @error('technologies')
            <div class="text-danger">{{ $message }}</div>
        @enderror

</div>
```

EDITARE LA ***StoreProjectRequest*** AGGIUNGENDO LE REGOLE PER TECHNOLOGIES
```php
'technologies' => 'nullable|exists:technologies,id', // PUO' NON ESSERE SELEZIONATO E DEVE ESISTERE NELLA COLONNA DEGLI ID
```

AGGIUNGERE AL METODO ***store()*** IN ***ProjectController*** LE TECHNOLOGIES USANDO IL METODO ***attach()***
https://laravel.com/docs/10.x/eloquent-relationships#attaching-detaching

```php
$newProject->technologies()->attach($request->technologies);
```

VISUALIZZARE LE TECH USATE NEL MARKUP
```php
<ul class="d-flex gap-2 list-unstyled">
    @forelse ($project->technologies as $technology)
        <li class="badge bg-success">
            <i class="fa-solid fa-code"></i> {{ $technology->name }}
        </li>
    @empty
        <li class="badge bg-secondary"><i class="fa-regular fa-file"></i> None/Others</li>
    @endforelse
</ul>
```

AGGIORNARE IL METODO ***edit()*** NEL ***ProjectController***
```php
public function edit(Project $project)
    {
        $page_title = 'Edit';

        $types = Type::all();
        $technologies = Technology::all();
        return view('admin.projects.edit', compact('project', 'page_title', 'types', 'technologies'));
    }
```

MODIFICARE LA VISTA ***admin.projects.create*** E AGGIUNGERE IL FORM PER SELEZIONARE LE TECHNOLOGIES

```php
<label for="technologies" class="form-label"><strong>Technologies Used</strong></label>
    <select multiple class="form-select form-select" name="technologies[]" id="technologies">
        <option disabled>Select Technologies used</option>
            @foreach ($technologies as $technology)
                @if ($errors->any())

                    // SE VI SONO ERRORI CONTROLLA SE L'ID DELLA TECHNOLOGY CICLATA E' CONTENUTO DENTRO old('technologies')
                    // SE VI SONO CORRISPONDEZE LE PRESELEZIONA
                    // SE L'ARRAY OLD NON ESISTE CONFRONTA UN ARRAY VUOTO [] COME FALLBACK, AUTOMATICAMENTE NON TROVANDO CORRISPONDENZE E NON SELEZIONANDO NULLA
                    <option value="{{ $technology->id }}"
                    {{ in_array($technology->id, old('technologies', [])) ? 'selected' : '' }}>
                        {{ $technology->name }}
                    </option>

                @else

                    // SE $project->technologies CONTIENE LA TECHNOLOGY CICLATA LA SELEZIONA
                    <option value="{{ $technology->id }}"
                    {{ $project->technologies->contains($technology) ? 'selected' : '' }}>
                        {{ $technology->name }}</option>
                @endif
            @endforeach
    </select>

@error('technologies')
    <div class="text-danger">{{ $message }}</div>
@enderror
```

MODIFICARE IL METODO ***update()*** in ***ProjectController*** E AGGIUNGERE UN COLTROLLO SULLA PRESENZA DI ***technologies*** nella ***UpdateProjectRequest***
USARE IL METODO ***synch()*** per AGGIORNARE IN MASSA LE RELAZIONI
https://laravel.com/docs/10.x/eloquent-relationships#syncing-associations
```php
if ($request->has('technologies')) {
    $project->technologies()->sync($request->technologies); // (o valData['technologies'])
}
```

MODIFICARE IL METODO ***destroy()*** (O ***forcedelete()***) in ***ProjectController*** CON IL METODO detach() PER RIMUOVERE LE RELAZIONII NELLA PIVOT PRIMA DELL'ELIMINAZIONE
https://laravel.com/docs/10.x/eloquent-relationships#attaching-detaching
```php
$project->technologies()->detach();
```

CREARE IL RESOURCE CONTROLLER PER AGGIUNGERE NUOVE TECHNOLOGIES
```bash
php artisan make:controller --resource Admin/TechnologyController --resource --model=Technology
```

CREARE LE FORM REQUESTS PER IL MODELLO ***Technology*** IN MODO DA IMPLEMENTARE LE CRUDS
```bash
php artisan make:request StoreTechnologyRequest
```

```bash
php artisan make:request UpdateTechnologyRequest
```

 EDITARE ***StoreTechnologyRequest*** E ***UpdateTechnologyRequest***
```php
public function rules(): array
    {
        return [
            // IL NOME E' RICHIESTO, HA UNA LUNGHEZZA 3/50 E DEVE ESSERE UNICO ALL'INTERNO DEL CAMPO name NELLA TABELLA technologies
            'name' => 'required|bail|min:3|max:50|unique:technologies,name' 
        ];
    }
```

AGGIUNGERE AL MODELLO ***Technology*** I CAMPI $fillable ED EVENTUALMENTE IL METODO PER GENERARE LO SLUG
```php
protected $fillable = ['name', 'slug'];

    public static function generateSlug($name)
    {
        return Str::slug($name, '-');
    }
```
AGGIUNGERE LE ROUTES IN ***web.php*** ALL'INTERNO DEL ROUTES GROUP DELL'ADMIN
```php
// TECHNOLOGIES RESOURCE CONTROLLER ROUTES
        Route::resource('technologies', TechnologyController::class)->parameters(['technologies' => 'technology:slug']);
```

INSERIRE LE CRUDS E LE VISTE NECESSARIE

***destroy()*** DELLE TECHNOLOGIES GIA' ASSOCIATE A UN PROJECT
```php
public function destroy(Technology $technology)
    {
        $projects = Project::withTrashed();

        foreach ($projects as $project) {
            if ($project->technologies) {
                $project->technologies()->detach($technology->id);
            }
        }

        $technology->delete();
        return to_route('admin.technologies.index')->with('status', 'Well Done, Element deleted Succeffully'); 
    }
```

***destroy()*** DI UN TYPE GIA' ASSOCIATO A UN PROJECT
PER ELIMINARE L'ISTANZA DI UNA CLASSE CHE BelongsTo UN'ALTRA CLASSE BISOGNA PRIMA DISSOCIARLA
https://laravel.com/docs/10.x/eloquent-relationships#updating-belongs-to-relationships

TypeController ***destroy()*** METHOD
```php
public function destroy(Type $type)
    {
        $projects = Project::withTrashed()->has('type')->get(); // RECUPERIAMO I PROGETTI CHE HANNO UN TYPE

        // CICLIAMO I PROGETTI
        foreach ($projects as $project) {
            // QUANDO TROVIAMO UN PROGETTO IL CUI TYPE HA UN ID UGUALE A QUELLO DEL TYPE CHESTIAMO ELIMINANDO
            if ($project->type->id == $type->id) {
                // DISSOCIAMO IL TYPE
                $project->type()->dissociate();
                // NON DIMENTICHIAMO DI SALVARE
                $project->save();
            }
        }

        $type->delete();
        return to_route('admin.types.index')->with('status', 'Well Done, Element deleted Succeffully'); 
    }
```

## Extra: Checkboxes

***create.blade()***
```php
<label class="form-label d-block"><strong>Technologies Used:</strong></label>
    <div class="card p-2 d-flex flex-row">
        @foreach ($technologies as $technology)
            <div class="form-check mx-1">

                {{-- VIENE DATO UN ARRAY COME NAME PER ACCETTARE SCELTE MULTIPLE --}}
                <input class="form-check-input @error('technologies') is-invalid @enderror"
                type="checkbox" id="technologies{{ $technology->id }}" name="technologies[]"
                aria-describedby="helpTechnology" 
                value="{{ $technology->id }}"

                // CONFRONTA L'ARRAY DEGLI ID DELLE TECHNOLOGIES CON QUELLO CONTENENTE I CAMPI SELEZIONATI PRECEDENTEMENTE
                // SE VI SONO CORRISPONDENZE LI PRESELEZIONA
                // SE L'ARRAY OLD NON ESISTE CONFRONTA UN ARRAY VUOTO [] COME FALLBACK, AUTOMATICAMENTE NON TROVANDO CORRISPONDENZE E NON SELEZIONANDO NULLA
                {{ in_array($technology->id, old('technologies', [])) ? 'checked' : '' }}>

                <label class="form-check-label" for="technologies{{ $technology->id }}">{{ $technology->name }}</label>

            </div>
        @endforeach
    </div>

<div id="helpTechnology" class="form-text">
    Check the Technologies used in your Project.
</div>

@error('technologies')
    <div class="text-danger">{{ $message }}</div>
@enderror
```

***edit.blade()***
```php
<label class="form-label d-block"><strong>Technologies Used:</strong></label>
    <div class="card p-2 d-flex flex-row">
        @foreach ($technologies as $technology)
            <div class="form-check mx-1">

                {{-- VIENE DATO UN ARRAY COME NAME PER ACCETTARE SCELTE MULTIPLE --}}
                @if ($errors->any())
                    <input class="form-check-input @error('technologies') is-invalid @enderror"
                        type="checkbox" id="technologies{{ $technology->id }}" name="technologies[]"
                        aria-describedby="helpTechnology" 
                        value="{{ $technology->id }}"
                                            
                        // CONFRONTA L'ARRAY DEGLI ID DELLE TECHNOLOGIES CON QUELLO CONTENENTE I CAMPI SELEZIONATI PRECEDENTEMENTE
                        // SE VI SONO CORRISPONDENZE LI PRESELEZIONA
                        // SE L'ARRAY OLD NON ESISTE CONFRONTA UN ARRAY VUOTO [] COME FALLBACK, AUTOMATICAMENTE NON TROVANDO CORRISPONDENZE E NON SELEZIONANDO NULLA --}}
                        {{ in_array($technology->id, old('technologies', [])) ? 'checked' : '' }}>
                @else
                    <input class="form-check-input @error('technologies') is-invalid @enderror"
                        type="checkbox" id="technologies{{ $technology->id }}" 
                        name="technologies[]"
                        aria-describedby="helpTechnology" 
                        value="{{ $technology->id }}"

                        // SE $project->technologies CONTIENE LA TECHNOLOGY CICLATA LA SELEZIONA
                        {{ $project->technologies->contains($technology) ? 'checked' : '' }}>
                @endif

                <label class="form-check-label" for="technologies{{ $technology->id }}">{{ $technology->name }}</label>

            </div>
        @endforeach
    </div>

<div id="helpTechnology" class="form-text">
    Check the Technologies used in your Project.
</div>

@error('technologies')
    <div class="text-danger">{{ $message }}</div>
@enderror
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
