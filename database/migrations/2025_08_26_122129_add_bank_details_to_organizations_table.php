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
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('account_holder_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('bank_name')->nullable();
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
            ])->nullable();
            $table->text('qr_payment_img')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn([
                'account_holder',
                'ifsc_code',
                'branch_name',
                'bank_name',
                'state',
                'qr_payment_img',
            ]);
        });
    }
};
