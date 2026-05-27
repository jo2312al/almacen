<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "periodo".
 *
 * @property int $per_id
 * @property string $per_nombre
 *
 * @property Servicio[] $servicios
 */
class Periodo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'periodo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['per_nombre'], 'required'],
            [['per_nombre'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'per_id' => 'Per ID',
            'per_nombre' => 'Per Nombre',
        ];
    }

    /**
     * Gets query for [[Servicios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getServicios()
    {
        return $this->hasMany(Servicio::class, ['ser_periodo_id' => 'per_id']);
    }
}
