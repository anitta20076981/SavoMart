<?php

use App\Models\Attribute;
use App\Models\AttributeSet;
use App\Models\AttributeSetAttributes;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        AttributeSet::create(['id' => 1, 'name' => 'Default', 'status' => 'active']);

        Attribute::create(['name' => 'Color', 'code' => 'color', 'status' => 'active', 'input_type' => 'visualswatch', 'is_required' => '1']);
        Attribute::create(['name' => 'Size', 'code' => 'size', 'status' => 'active', 'input_type' => 'textswatch', 'is_required' => '1']);

        $defaultAttributes = ['color', 'size'];

        if ($defaultAttributes) {
            foreach ($defaultAttributes as $defaultAttribute) {
                $attribute = Attribute::where('code', $defaultAttribute)->first();

                if ($attribute) {
                    $input = [
                        'attribute_set_id' => 1,
                        'attribute_id' => $attribute->id,
                    ];
                    AttributeSetAttributes::create($input);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        AttributeSet::where('id', 1)->delete();

        Attribute::whereIn('code', ['color', 'size'])->delete();
    }
};
