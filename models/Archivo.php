<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile; // Asegúrate de que esta línea esté presente

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
    /**
     * @var UploadedFile Atributo virtual para manejar la subida del archivo.
     */
    public $file;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'archivo';
    }

    /**
     * ===================================================================
     * FUNCIÓN 'RULES' CORREGIDA
     * - Se quita 'arc_ruta' de la regla 'required'.
     * - Se añade 'file' como requerido en el escenario 'create'.
     * - Se añade un validador de tipo 'file'.
     * ===================================================================
     */
    public function rules()
    {
        return [
            // --- REGLAS DE DATOS ---
            // Campos que son requeridos para que el registro tenga sentido.
            [['arc_codigo', 'arc_nombre_documento', 'arc_alumno_id', 'arc_caja_id'], 'required', 'message' => '{attribute} no puede estar vacío.'],
            
            // Campos que deben ser números enteros.
            [['arc_caja_id', 'arc_alumno_id', 'arc_fondo_id', 'arc_clave_programatica_id', 'arc_area_generadora_id', 'arc_seccion_serie_id'], 'integer'],
            
            // Longitud máxima de los campos de texto.
            [['arc_codigo', 'arc_nombre_documento'], 'string', 'max' => 100],
            [['arc_ruta'], 'string', 'max' => 255],

            // --- REGLAS DE ARCHIVO ---
            // El archivo (file) es requerido solo al crear. No al actualizar.
            [['file'], 'required', 'on' => 'create', 'message' => 'Debe seleccionar un archivo PDF para subir.'],

            // Validador de archivo: solo permite PDFs y con un tamaño máximo de 10MB (ajustable).
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf', 'maxSize' => 1024 * 1024 * 10, 'on' => 'create'],

            // --- REGLAS DE RELACIONES (EXIST) ---
            // Aseguran que los IDs existan en sus tablas correspondientes.
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
            'arc_id' => 'ID',
            'arc_codigo' => 'Código Clasificador',
            'arc_nombre_documento' => 'Nombre del Documento',
            'arc_caja_id' => 'Caja',
            'arc_alumno_id' => 'Alumno',
            'arc_ruta' => 'Ruta del Archivo',
            'arc_fondo_id' => 'Fondo',
            'arc_clave_programatica_id' => 'Clave Programática',
            'arc_area_generadora_id' => 'Área Generadora',
            'arc_seccion_serie_id' => 'Sección Serie',
            'file' => 'Archivo PDF', // Etiqueta para el campo de subida
        ];
    }

    // --- El resto de tus funciones (getArcAlumno, getArcCaja, etc.) permanecen igual ---

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
