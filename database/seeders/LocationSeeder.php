<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Seeder;

/**
 * Starter geography: 25 popular countries with dial codes, states for the
 * eight biggest ones and a handful of major cities. Admins can extend the
 * catalogue from Settings → Locations; the phone parser only needs the
 * dial codes to be present, so this keeps global OTP working out of the box.
 */
class LocationSeeder extends Seeder
{
    public function run(): void
    {
        if (Country::count() > 0) {
            return;
        }

        $popularCountries = [
            ['name' => 'United States', 'code' => 'US', 'phone_code' => '+1'],
            ['name' => 'Canada', 'code' => 'CA', 'phone_code' => '+1'],
            ['name' => 'United Kingdom', 'code' => 'GB', 'phone_code' => '+44'],
            ['name' => 'Germany', 'code' => 'DE', 'phone_code' => '+49'],
            ['name' => 'France', 'code' => 'FR', 'phone_code' => '+33'],
            ['name' => 'Australia', 'code' => 'AU', 'phone_code' => '+61'],
            ['name' => 'India', 'code' => 'IN', 'phone_code' => '+91'],
            ['name' => 'United Arab Emirates', 'code' => 'AE', 'phone_code' => '+971'],
            ['name' => 'Saudi Arabia', 'code' => 'SA', 'phone_code' => '+966'],
            ['name' => 'Pakistan', 'code' => 'PK', 'phone_code' => '+92'],
            ['name' => 'Bangladesh', 'code' => 'BD', 'phone_code' => '+880'],
            ['name' => 'Netherlands', 'code' => 'NL', 'phone_code' => '+31'],
            ['name' => 'Spain', 'code' => 'ES', 'phone_code' => '+34'],
            ['name' => 'Italy', 'code' => 'IT', 'phone_code' => '+39'],
            ['name' => 'Brazil', 'code' => 'BR', 'phone_code' => '+55'],
            ['name' => 'Mexico', 'code' => 'MX', 'phone_code' => '+52'],
            ['name' => 'Japan', 'code' => 'JP', 'phone_code' => '+81'],
            ['name' => 'China', 'code' => 'CN', 'phone_code' => '+86'],
            ['name' => 'South Korea', 'code' => 'KR', 'phone_code' => '+82'],
            ['name' => 'South Africa', 'code' => 'ZA', 'phone_code' => '+27'],
            ['name' => 'Nigeria', 'code' => 'NG', 'phone_code' => '+234'],
            ['name' => 'Egypt', 'code' => 'EG', 'phone_code' => '+20'],
            ['name' => 'Turkey', 'code' => 'TR', 'phone_code' => '+90'],
            ['name' => 'Singapore', 'code' => 'SG', 'phone_code' => '+65'],
            ['name' => 'Malaysia', 'code' => 'MY', 'phone_code' => '+60'],
        ];

        Country::insert($popularCountries);

        $this->seedUs();
        $this->seedCa();
        $this->seedIn();
        $this->seedAu();
        $this->seedGb();
        $this->seedDe();
        $this->seedFr();
        $this->seedAe();
    }

    private function countryId(string $code): int
    {
        return Country::where('code', $code)->value('id');
    }

    private function seedUs(): void
    {
        $countryId = $this->countryId('US');
        $states = [
            'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas',
            'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware',
            'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho',
            'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas',
            'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland',
            'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi',
            'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada',
            'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York',
            'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma',
            'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina',
            'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah',
            'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia',
            'WI' => 'Wisconsin', 'WY' => 'Wyoming',
            'DC' => 'District of Columbia', 'PR' => 'Puerto Rico', 'GU' => 'Guam',
        ];

        foreach ($states as $code => $name) {
            State::create(['country_id' => $countryId, 'name' => $name, 'code' => $code, 'is_active' => true]);
        }

        $cities = [
            'NY' => ['New York City', 'Buffalo', 'Rochester'],
            'CA' => ['Los Angeles', 'San Francisco', 'San Diego'],
            'TX' => ['Houston', 'Dallas', 'Austin'],
            'FL' => ['Miami', 'Orlando', 'Tampa'],
            'IL' => ['Chicago'],
            'PA' => ['Philadelphia', 'Pittsburgh'],
            'AZ' => ['Phoenix', 'Tucson'],
            'WA' => ['Seattle'],
            'MA' => ['Boston'],
            'MI' => ['Detroit'],
            'GA' => ['Atlanta'],
            'NC' => ['Charlotte'],
            'OH' => ['Columbus', 'Cleveland'],
            'NV' => ['Las Vegas'],
            'CO' => ['Denver'],
        ];

        foreach ($cities as $code => $names) {
            $state = State::where('country_id', $countryId)->where('code', $code)->first();
            if (! $state) {
                continue;
            }
            foreach ($names as $city) {
                City::create(['state_id' => $state->id, 'country_id' => $countryId, 'name' => $city, 'is_active' => true]);
            }
        }
    }

    private function seedCa(): void
    {
        $countryId = $this->countryId('CA');
        $provinces = [
            'AB' => 'Alberta', 'BC' => 'British Columbia', 'MB' => 'Manitoba',
            'NB' => 'New Brunswick', 'NL' => 'Newfoundland and Labrador',
            'NS' => 'Nova Scotia', 'ON' => 'Ontario', 'PE' => 'Prince Edward Island',
            'QC' => 'Quebec', 'SK' => 'Saskatchewan', 'NT' => 'Northwest Territories',
            'NU' => 'Nunavut', 'YT' => 'Yukon',
        ];

        foreach ($provinces as $code => $name) {
            State::create(['country_id' => $countryId, 'name' => $name, 'code' => $code, 'is_active' => true]);
        }

        $cities = [
            'ON' => ['Toronto', 'Ottawa'],
            'QC' => ['Montreal'],
            'BC' => ['Vancouver'],
            'AB' => ['Calgary'],
        ];

        foreach ($cities as $code => $names) {
            $state = State::where('country_id', $countryId)->where('code', $code)->first();
            if (! $state) {
                continue;
            }
            foreach ($names as $city) {
                City::create(['state_id' => $state->id, 'country_id' => $countryId, 'name' => $city, 'is_active' => true]);
            }
        }
    }

    private function seedIn(): void
    {
        $countryId = $this->countryId('IN');
        $states = [
            'AP' => 'Andhra Pradesh', 'AR' => 'Arunachal Pradesh', 'AS' => 'Assam', 'BR' => 'Bihar',
            'CT' => 'Chhattisgarh', 'GA' => 'Goa', 'GJ' => 'Gujarat', 'HR' => 'Haryana',
            'HP' => 'Himachal Pradesh', 'JH' => 'Jharkhand', 'KA' => 'Karnataka', 'KL' => 'Kerala',
            'MP' => 'Madhya Pradesh', 'MH' => 'Maharashtra', 'MN' => 'Manipur', 'ML' => 'Meghalaya',
            'MZ' => 'Mizoram', 'NL' => 'Nagaland', 'OR' => 'Odisha', 'PB' => 'Punjab',
            'RJ' => 'Rajasthan', 'SK' => 'Sikkim', 'TN' => 'Tamil Nadu', 'TG' => 'Telangana',
            'TR' => 'Tripura', 'UP' => 'Uttar Pradesh', 'UK' => 'Uttarakhand', 'WB' => 'West Bengal',
            'AN' => 'Andaman and Nicobar Islands', 'CH' => 'Chandigarh', 'DH' => 'Dadra and Nagar Haveli and Daman and Diu',
            'DL' => 'Delhi', 'JK' => 'Jammu and Kashmir', 'LA' => 'Ladakh', 'LD' => 'Lakshadweep',
            'PY' => 'Puducherry',
        ];

        foreach ($states as $code => $name) {
            State::create(['country_id' => $countryId, 'name' => $name, 'code' => $code, 'is_active' => true]);
        }

        $cities = [
            'MH' => ['Mumbai', 'Pune', 'Nagpur'],
            'DL' => ['Delhi'],
            'KA' => ['Bengaluru'],
            'TN' => ['Chennai'],
            'WB' => ['Kolkata'],
            'TG' => ['Hyderabad'],
            'GJ' => ['Ahmedabad', 'Surat'],
            'RJ' => ['Jaipur'],
            'UP' => ['Lucknow'],
            'KL' => ['Kochi'],
            'PB' => ['Amritsar'],
        ];

        foreach ($cities as $code => $names) {
            $state = State::where('country_id', $countryId)->where('code', $code)->first();
            if (! $state) {
                continue;
            }
            foreach ($names as $city) {
                City::create(['state_id' => $state->id, 'country_id' => $countryId, 'name' => $city, 'is_active' => true]);
            }
        }
    }

    private function seedAu(): void
    {
        $countryId = $this->countryId('AU');
        $states = [
            'NSW' => 'New South Wales', 'VIC' => 'Victoria', 'QLD' => 'Queensland',
            'WA' => 'Western Australia', 'SA' => 'South Australia', 'TAS' => 'Tasmania',
            'ACT' => 'Australian Capital Territory', 'NT' => 'Northern Territory',
        ];

        foreach ($states as $code => $name) {
            $state = State::create(['country_id' => $countryId, 'name' => $name, 'code' => $code, 'is_active' => true]);
            $city = match ($code) {
                'NSW' => 'Sydney',
                'VIC' => 'Melbourne',
                'QLD' => 'Brisbane',
                'WA' => 'Perth',
                'SA' => 'Adelaide',
                'TAS' => 'Hobart',
                'ACT' => 'Canberra',
                'NT' => 'Darwin',
            };
            City::create(['state_id' => $state->id, 'country_id' => $countryId, 'name' => $city, 'is_active' => true]);
        }
    }

    private function seedGb(): void
    {
        $countryId = $this->countryId('GB');
        $regions = [
            'ENG' => 'England', 'SCT' => 'Scotland', 'WAL' => 'Wales', 'NIR' => 'Northern Ireland',
        ];

        foreach ($regions as $code => $name) {
            State::create(['country_id' => $countryId, 'name' => $name, 'code' => $code, 'is_active' => true]);
        }
    }

    private function seedDe(): void
    {
        $countryId = $this->countryId('DE');
        $states = [
            'BW' => 'Baden-Württemberg', 'BY' => 'Bavaria', 'BE' => 'Berlin', 'BB' => 'Brandenburg',
            'HB' => 'Bremen', 'HH' => 'Hamburg', 'HE' => 'Hesse', 'MV' => 'Mecklenburg-Vorpommern',
            'NI' => 'Lower Saxony', 'NW' => 'North Rhine-Westphalia', 'RP' => 'Rhineland-Palatinate',
            'SL' => 'Saarland', 'SN' => 'Saxony', 'ST' => 'Saxony-Anhalt', 'SH' => 'Schleswig-Holstein',
            'TH' => 'Thuringia',
        ];

        foreach ($states as $code => $name) {
            State::create(['country_id' => $countryId, 'name' => $name, 'code' => $code, 'is_active' => true]);
        }
    }

    private function seedFr(): void
    {
        $countryId = $this->countryId('FR');
        $regions = [
            'ARA' => 'Auvergne-Rhône-Alpes', 'BFC' => 'Bourgogne-Franche-Comté', 'BRE' => 'Brittany',
            'CVL' => 'Centre-Val de Loire', 'COR' => 'Corsica', 'GES' => 'Grand Est',
            'HDF' => 'Hauts-de-France', 'IDF' => 'Île-de-France', 'NOR' => 'Normandy',
            'NAQ' => 'Nouvelle-Aquitaine', 'OCC' => 'Occitanie', 'PDL' => 'Pays de la Loire',
            'PAC' => "Provence-Alpes-Côte d'Azur",
        ];

        foreach ($regions as $code => $name) {
            State::create(['country_id' => $countryId, 'name' => $name, 'code' => $code, 'is_active' => true]);
        }
    }

    private function seedAe(): void
    {
        $countryId = $this->countryId('AE');
        $emirates = [
            'DXB' => 'Dubai', 'AUH' => 'Abu Dhabi', 'SHJ' => 'Sharjah', 'AJM' => 'Ajman',
            'FUJ' => 'Fujairah', 'RAK' => 'Ras Al Khaimah', 'UMQ' => 'Umm Al Quwain',
        ];

        foreach ($emirates as $code => $name) {
            State::create(['country_id' => $countryId, 'name' => $name, 'code' => $code, 'is_active' => true]);
        }
    }
}
