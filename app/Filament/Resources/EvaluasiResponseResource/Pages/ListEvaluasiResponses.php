<?php

namespace App\Filament\Resources\EvaluasiResponseResource\Pages;

use App\Filament\Resources\EvaluasiResponseResource;
use Filament\Resources\Pages\ListRecords;

class ListEvaluasiResponses extends ListRecords
{
    protected static string $resource = EvaluasiResponseResource::class;

    public function getTitle(): string
    {
        return "Monitoring Evaluasi";
    }
}
