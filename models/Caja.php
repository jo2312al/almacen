<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "caja".
 *
 * @property int $caj_id
 * @property string $caj_codigo
 * @property int|null $caj_anaquel_id
 * @property int|null $caj_nivel_id
 *
 * @property Archivo[] $archivos
 * @property Anaquel $cajAnaquel
 * @property Nivelalmacenamiento $cajNivel
 */
class Caja extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'caja';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['caj_codigo'], 'required'],
            [['caj_anaquel_id', 'caj_nivel_id'], 'integer'],
            [['caj_codigo'], 'string', 'max' => 50],
            [['caj_anaquel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Anaquel::class, 'targetAttribute' => ['caj_anaquel_id' => 'ana_id']],
            [['caj_nivel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Nivelalmacenamiento::class, 'targetAttribute' => ['caj_nivel_id' => 'niv_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'caj_id' => 'Caj ID',
            'caj_codigo' => 'Caj Codigo',
            'caj_anaquel_id' => 'Caj Anaquel ID',
            'caj_nivel_id' => 'Caj Nivel ID',
        ];
    }

    /**
     * Gets query for [[Archivos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArchivos()
    {
        return $this->hasMany(Archivo::class, ['arc_caja_id' => 'caj_id']);
    }

    /**
     * Gets query for [[CajAnaquel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCajAnaquel()
    {
        return $this->hasOne(Anaquel::class, ['ana_id' => 'caj_anaquel_id']);
    }

    /**
     * Gets query for [[CajNivel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCajNivel()
    {
        return $this->hasOne(Nivelalmacenamiento::class, ['niv_id' => 'caj_nivel_id']);
    }
}
