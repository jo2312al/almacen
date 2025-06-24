<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "archivo".
 *
 * @property int $arc_id
 * @property string $arc_codigo
 * @property string $arc_nombre_documento
 * @property int|null $arc_caja_id
 * @property int|null $arc_alumno_id
 * @property string $arc_ruta
 * @property int|null $arc_fondo_id
 * @property int|null $arc_clave_programatica_id
 * @property int|null $arc_area_generadora_id
 * @property int|null $arc_seccion_serie_id
 *
 * @property Alumno $arcAlumno
 * @property AreaGeneradora $arcAreaGeneradora
 * @property Caja $arcCaja
 * @property ClaveProgramatica $arcClaveProgramatica
 * @property Fondo $arcFondo
 * @property SeccionSerie $arcSeccionSerie
 */
class Archivo extends \yii\db\ActiveRecord
{
    public $file; // Atributo virtual para el campo de archivo

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'archivo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['arc_caja_id', 'arc_alumno_id', 'arc_fondo_id', 'arc_clave_programatica_id', 'arc_area_generadora_id', 'arc_seccion_serie_id'], 'default', 'value' => null],
            [['arc_codigo', 'arc_nombre_documento', 'arc_ruta'], 'required'],
            [['arc_caja_id', 'arc_alumno_id', 'arc_fondo_id', 'arc_clave_programatica_id', 'arc_area_generadora_id', 'arc_seccion_serie_id'], 'integer'],
            [['arc_codigo', 'arc_nombre_documento'], 'string', 'max' => 100],
            [['arc_ruta'], 'string', 'max' => 255],
            [['arc_alumno_id'], 'exist', 'skipOnError' => true, 'targetClass' => Alumno::class, 'targetAttribute' => ['arc_alumno_id' => 'alu_id']],
            [['arc_area_generadora_id'], 'exist', 'skipOnError' => true, 'targetClass' => AreaGeneradora::class, 'targetAttribute' => ['arc_area_generadora_id' => 'are_id']],
            [['arc_caja_id'], 'exist', 'skipOnError' => true, 'targetClass' => Caja::class, 'targetAttribute' => ['arc_caja_id' => 'caj_id']],
            [['arc_clave_programatica_id'], 'exist', 'skipOnError' => true, 'targetClass' => ClaveProgramatica::class, 'targetAttribute' => ['arc_clave_programatica_id' => 'cla_id']],
            [['arc_fondo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fondo::class, 'targetAttribute' => ['arc_fondo_id' => 'fon_id']],
            [['arc_seccion_serie_id'], 'exist', 'skipOnError' => true, 'targetClass' => SeccionSerie::class, 'targetAttribute' => ['arc_seccion_serie_id' => 'sec_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'arc_id' => 'Arc ID',
            'arc_codigo' => 'Arc Codigo',
            'arc_nombre_documento' => 'Arc Nombre Documento',
            'arc_caja_id' => 'Arc Caja ID',
            'arc_alumno_id' => 'Arc Alumno ID',
            'arc_ruta' => 'Arc Ruta',
            'arc_fondo_id' => 'Arc Fondo ID',
            'arc_clave_programatica_id' => 'Arc Clave Programatica ID',
            'arc_area_generadora_id' => 'Arc Area Generadora ID',
            'arc_seccion_serie_id' => 'Arc Seccion Serie ID',
        ];
    }

    /**
     * Gets query for [[ArcAlumno]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArcAlumno()
    {
        return $this->hasOne(Alumno::class, ['alu_id' => 'arc_alumno_id']);
    }

    /**
     * Gets query for [[ArcAreaGeneradora]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArcAreaGeneradora()
    {
        return $this->hasOne(AreaGeneradora::class, ['are_id' => 'arc_area_generadora_id']);
    }

    /**
     * Gets query for [[ArcCaja]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArcCaja()
    {
        return $this->hasOne(Caja::class, ['caj_id' => 'arc_caja_id']);
    }

    /**
     * Gets query for [[ArcClaveProgramatica]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArcClaveProgramatica()
    {
        return $this->hasOne(ClaveProgramatica::class, ['cla_id' => 'arc_clave_programatica_id']);
    }

    /**
     * Gets query for [[ArcFondo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArcFondo()
    {
        return $this->hasOne(Fondo::class, ['fon_id' => 'arc_fondo_id']);
    }

    /**
     * Gets query for [[ArcSeccionSerie]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArcSeccionSerie()
    {
        return $this->hasOne(SeccionSerie::class, ['sec_id' => 'arc_seccion_serie_id']);
    }

}
