<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class Export implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function headings():array{
        return [
            "Data ultimo pagamento",
            "Nome", "Cognome",
            "Presso", 
            "Indirizzo", "CAP", "Città", "Provincia",
            "Telefono", "Cellulare",
            "Email",
            "Categoria",
            "Sezioni",
            "Socio",
        ];
    } 

    public function view(): View
    {
        return view('exports.customers', [
            'customers' => $this->data
        ]);
    }
}
