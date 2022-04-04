<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use PragmaRX\Countries\Package\Countries;
use WhiteCube\Lingua\Service as Lingua;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = new Countries();
        $countries
            ->where('independent', '===', true)
            ->sortBy('name.common')
            ->all()
            ->each(
                function ($country) {
                    $newCountry = Country::updateOrCreate(
                        [
                            'iso_alpha_2' => $country->cca2,
                        ],
                        [
                            'name' => $country->name->get('common'),
                            'official_name' => $country->name->get('official'),
                            'abbreviation' => $country->get('abbrev'),
                            'capital' => $country->get('capital_rinvex'),

                            'iso_alpha_3' => $country->get('cca2'),
                            'iso_numeric' => $country->get('ccn3'),

                            'calling_code' => $country->has('calling_codes') ? $country->calling_codes->first() : null,

                            'tld' => $country->has('tld') ? $country->tld->first() : null,

                            'emoji' => $country->flag->get('emoji'),
                        ]
                    );

                    if (!$country->translations) return;

                    $country->translations->all()->each(function ($value, $locale) use ($newCountry) {
                        $language = Lingua::createFromISO_639_2t($locale);

                        $newCountry
                            ->setTranslation('name', $language->toISO_639_1(), $value->common)
                            ->setTranslation('official_name', $language->toISO_639_1(), $value->official);
                    });

                    $newCountry->save();
                });
    }
}
