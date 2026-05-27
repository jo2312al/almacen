<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "anaquel".
 *
 * @property int $ana_id
 * @property string $ana_nombre
 *
 * @property Caja[] $cajas
 */
class Anaquel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'anaquel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ana_nombre'], 'required'],
            [['ana_nombre'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ana_id' => 'Ana ID',
            'ana_nombre' => 'Ana Nombre',
        ];
    }

    /**
     * Gets query for [[Cajas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCajas()
    {
        return $this->hasMany(Caja::class, ['caj_anaquel_id' => 'ana_id']);
    }
}
