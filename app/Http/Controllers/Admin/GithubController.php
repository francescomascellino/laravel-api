<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GithubController extends Controller
{

    public function fetchRepos()
    {
        $username = 'francescomascellino';

        // PROBLEMI DI CERTIFICATI
        /* $response = Http::withoutVerifying()
            ->get("https://877c697d-eb4a-43b7-9c7c-0ec1d8610218.mock.pstmn.io/https://api.github.com/users/$username/repos?per_page=5"); */

        // OLD (30 PROJECTS)
        /* $response = Http::withHeaders([
            'Authorization' => 'TOKEN',
            ])->withoutVerifying()->get("https://877c697d-eb4a-43b7-9c7c-0ec1d8610218.mock.pstmn.io/https://api.github.com/users/francescomascellino/repos?per_page=30&sort=created"); */

        $response = Http::withHeaders([
            'Authorization' => env('GITHUB_TOKEN'),
        ])->withoutVerifying()->get("https://877c697d-eb4a-43b7-9c7c-0ec1d8610218.mock.pstmn.io/https://api.github.com/users/francescomascellino/repos?per_page=100&sort=created");

        if ($response->successful()) {
            $repositories = $response->json();

            foreach ($repositories as $repository) {
                $slug = Str::slug($repository['name']);

                // AGGIORNA O CREA IL PROGETTO
                $project = Project::updateOrCreate(
                    ['title' => $repository['name']],
                    [
                        'slug' => $slug,
                        'thumb' => 'placeholders/placeholder.jpg',
                        'github' => $repository['html_url'],
                        'description' => $repository['description'],
                    ]
                );

                // OTTIENE I LINGUAGGI CON LE PERCENTUALI PER OGNI SINGOLA REPO CICLATA
                $languagesResponse = Http::withHeaders([
                    'Authorization' => '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',
                ])->withoutVerifying()
                    ->get("https://api.github.com/repos/{$username}/{$repository['name']}/languages");

                /* $languagesResponse = Http::withoutVerifying()
                    ->get("https://877c697d-eb4a-43b7-9c7c-0ec1d8610218.mock.pstmn.io/https://api.github.com/repos/{$username}/{$repository['name']}/languages"); */

                if ($languagesResponse->successful()) {
                    $languages = $languagesResponse->json();

                    // SE LA RESPONSE E' POSITIVA PRENDE LA CHIAVE language IGNORANDO PER ADESSO I BYTES
                    foreach ($languages as $language => $bytes) {

                        // AGGIORNA LA TECHNOLOGY EVITANDO DUPLICATI
                        $technology = Technology::firstOrCreate(
                            ['name' => $language],
                            ['slug' => Str::slug($language, '-')]
                        );

                        // CALCOLA LA PERCENTUALE
                        $totalBytes = array_sum($languages);
                        $percentage = ($bytes / $totalBytes) * 100;

                        // FORMATTA LA PERCENTUALE MANTENENDO LE ULTIME DUE CIFRE DECIMALI
                        $formattedPercentage = number_format($percentage, 2);

                        // AGGIUNGE LA RELAZIONE TRA PROGETTO E TECHNOLOGIA (SE NON PRESENTE) COMPRESA DI PERCENTUALE FORMATTATA
                        $project->technologies()->syncWithoutDetaching([
                            $technology->id => ['percentage' => $formattedPercentage]
                        ]);
                    }
                }
            }
        }
        return to_route('admin.projects.index')->with('status', 'Well Done, Repositories fetched Succeffully');
    }

    /*     public function fetchRepos()
    {
        $username = 'francescomascellino';

        // $response = Http::withoutVerifying()
        // ->get("https://api.github.com/users/$username/repos?per_page=100");

        $response = Http::withoutVerifying()
            ->get("https://877c697d-eb4a-43b7-9c7c-0ec1d8610218.mock.pstmn.io/https://api.github.com/users/$username/repos?per_page=50");

        // dd($response->body());

        if ($response->successful()) {
            $repositories = $response->json();

            dd($repositories);

            // AGGIORNA O CREA IL PROGETTO
            foreach ($repositories as $repository) {
                $slug = Str::slug($repository['name']);
                Project::updateOrCreate(

                    ['title' => $repository['name']],

                    [
                        'slug' => $slug,
                        'thumb' => 'placeholders/placeholder.jpg',
                        'github' => $repository['html_url'],
                        'description' => $repository['description'],
                        // 'languages_url' => $repository['languages_url'],
                    ]

                );

            }
        }
    }
 */
}
