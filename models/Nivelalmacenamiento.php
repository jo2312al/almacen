<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "nivelalmacenamiento".
 *
 * @property int $niv_id
 * @property string $niv_nombre
 *
 * @property Caja[] $cajas
 */
class Nivelalmacenamiento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'nivelalmacenamiento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['niv_nombre'], 'required'],
            [['niv_nombre'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'niv_id' => 'Niv ID',
            'niv_nombre' => 'Niv Nombre',
        ];
    }

    /**
     * Gets query for [[Cajas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCajas()
    {
        return $this->hasMany(Caja::class, ['caj_nivel_id' => 'niv_id']);
    }
}
