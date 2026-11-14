<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_location_endpoints_return_active_rows_for_cascading_dropdowns(): void
    {
        $country = Country::create(['name' => 'Bangladesh', 'code' => 'BD', 'phone_code' => '+880']);
        $dhaka = State::create(['country_id' => $country->id, 'name' => 'Dhaka', 'code' => 'DHK']);
        State::create(['country_id' => $country->id, 'name' => 'Hidden State', 'is_active' => false]);
        City::create(['state_id' => $dhaka->id, 'country_id' => $country->id, 'name' => 'Gazipur']);
        City::create(['state_id' => $dhaka->id, 'country_id' => $country->id, 'name' => 'Hidden City', 'is_active' => false]);

        $this->getJson(route('locations.states', ['country_id' => $country->id]))
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(['name' => 'Dhaka', 'code' => 'DHK']);

        $this->getJson(route('locations.cities', ['state_id' => $dhaka->id]))
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(['name' => 'Gazipur']);

        // Missing or empty parameters return an empty list, not an error
        $this->getJson(route('locations.states'))->assertOk()->assertExactJson([]);
        $this->getJson(route('locations.cities'))->assertOk()->assertExactJson([]);
    }

    public function test_super_admin_can_manage_countries_and_dial_codes(): void
    {
        $admin = Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@mono-lms.test',
            'password' => 'password',
            'is_super_admin' => true,
        ]);

        $country = Country::create(['name' => 'Testland', 'code' => 'TL', 'phone_code' => '+111']);

        // Create with a dial code
        $this->actingAs($admin, 'admin')
            ->post(route('admin.settings.countries.store'), [
                'name' => 'Bangladesh',
                'code' => 'BD',
                'phone_code' => '880',
                'is_active' => '1',
            ])
            ->assertRedirect();

        $created = Country::where('code', 'BD')->first();
        $this->assertNotNull($created);
        $this->assertEquals('+880', $created->phone_code);
        $this->assertTrue($created->is_active);

        // An enabled country without a dial code is rejected
        $this->actingAs($admin, 'admin')
            ->post(route('admin.settings.countries.store'), [
                'name' => 'No Dial',
                'code' => 'ND',
                'is_active' => '1',
            ])
            ->assertSessionHasErrors('phone_code');

        // Update reuses the code, dial code normalized
        $this->actingAs($admin, 'admin')
            ->put(route('admin.settings.countries.update', $country), [
                'name' => 'Testlandia',
                'code' => 'TL',
                'phone_code' => '+112',
                'is_active' => '1',
            ])
            ->assertRedirect();

        $this->assertEquals('Testlandia', $country->fresh()->name);
        $this->assertEquals('+112', $country->fresh()->phone_code);

        // Delete cascades to states/cities
        $state = State::create(['country_id' => $country->id, 'name' => 'Test State']);
        City::create(['state_id' => $state->id, 'country_id' => $country->id, 'name' => 'Test City']);

        $this->actingAs($admin, 'admin')
            ->delete(route('admin.settings.countries.destroy', $country))
            ->assertRedirect();

        $this->assertDatabaseMissing('countries', ['id' => $country->id]);
        $this->assertDatabaseMissing('states', ['id' => $state->id]);
        $this->assertDatabaseMissing('cities', ['name' => 'Test City']);
    }

    public function test_super_admin_can_manage_states_and_cities_per_country(): void
    {
        $admin = Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@mono-lms.test',
            'password' => 'password',
            'is_super_admin' => true,
        ]);

        $country = Country::create(['name' => 'Bangladesh', 'code' => 'BD', 'phone_code' => '+880']);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.settings.countries.states.store', $country), [
                'name' => 'Dhaka',
                'code' => 'DHK',
                'is_active' => '1',
            ])
            ->assertRedirect();

        $state = State::where('country_id', $country->id)->where('code', 'DHK')->first();
        $this->assertNotNull($state);

        $this->actingAs($admin, 'admin')
            ->put(route('admin.settings.countries.states.update', [$country, $state]), [
                'name' => 'Dhaka Division',
                'code' => 'DHK',
            ])
            ->assertRedirect();
        $this->assertEquals('Dhaka Division', $state->fresh()->name);
        $this->assertFalse($state->fresh()->is_active);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.settings.countries.states.cities.store', [$country, $state]), [
                'name' => 'Gazipur',
                'is_active' => '1',
            ])
            ->assertRedirect();

        $city = City::where('state_id', $state->id)->where('name', 'Gazipur')->first();
        $this->assertNotNull($city);
        $this->assertEquals($country->id, $city->country_id);

        $this->actingAs($admin, 'admin')
            ->delete(route('admin.settings.countries.states.cities.destroy', [$country, $state, $city]))
            ->assertRedirect();
        $this->assertDatabaseMissing('cities', ['id' => $city->id]);

        $this->actingAs($admin, 'admin')
            ->delete(route('admin.settings.countries.states.destroy', [$country, $state]))
            ->assertRedirect();
        $this->assertDatabaseMissing('states', ['id' => $state->id]);
    }

    public function test_non_authorized_admin_cannot_access_location_settings(): void
    {
        $admin = Admin::create([
            'name' => 'Plain Admin',
            'email' => 'plain@mono-lms.test',
            'password' => 'password',
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.settings.countries'))
            ->assertForbidden();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.settings.countries.store'), [
                'name' => 'Sneaky',
                'code' => 'SN',
            ])
            ->assertForbidden();
    }

    public function test_phones_backfill_command_populates_canonical_columns(): void
    {
        $student = Student::create([
            'name' => 'Backfill Student',
            'email' => 'backfill@mono-lms.test',
            'password' => 'password',
            'status' => 'active',
            'phone' => '+8801700000000',
        ]);

        $this->artisan('phones:backfill', ['--apply' => true, '--report' => 'reports/backfill-test.json'])
            ->assertSuccessful();

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'phone_e164' => '+8801700000000',
        ]);
        $this->assertNull($student->fresh()->phone_country);
    }
}
