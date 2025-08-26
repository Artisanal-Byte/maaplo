<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->enum('state', [
                'Andhra_Pradesh',
                'Arunachal_Pradesh',
                'Assam',
                'Bihar',
                'Chhattisgarh',
                'Goa',
                'Gujarat',
                'Haryana',
                'Himachal_Pradesh',
                'Jharkhand',
                'Karnataka',
                'Kerala',
                'Madhya_Pradesh',
                'Maharashtra',
                'Manipur',
                'Meghalaya',
                'Mizoram',
                'Nagaland',
                'Odisha',
                'Punjab',
                'Rajasthan',
                'Sikkim',
                'Tamil_Nadu',
                'Telangana',
                'Tripura',
                'Uttar_Pradesh',
                'Uttarakhand',
                'West_Bengal',
                'Andaman_and_Nicobar_Islands',
                'Chandigarh',
                'Dadra_and_Nagar_Haveli_and_Daman_and_Diu',
                'Delhi',
                'Jammu_and_Kashmir',
                'Ladakh',
                'Lakshadweep',
                'Puducherry'
            ])->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
};
