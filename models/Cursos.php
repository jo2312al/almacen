<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cursos".
 *
 * @property int $cur_curso_id
 * @property int $cur_idioma_id ID del Idioma
 * @property string $cur_nombre Nombre del Curso
 * @property string $cur_descripcion Descripción del Curso
 * @property int $cur_nivel_id ID del Nivel
 * @property string $cur_fecha Fecha de Inicio
 *
 * @property Idiomas $curIdioma
 * @property Nivel $curNivel
 * @property EvaluacionCurso[] $evaluacionCursos
 * @property Temas[] $temas
 */
class Cursos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cursos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cur_idioma_id', 'cur_nombre', 'cur_descripcion', 'cur_nivel_id', 'cur_fecha'], 'required'],
            [['cur_idioma_id', 'cur_nivel_id'], 'integer'],
            [['cur_descripcion'], 'string'],
            [['cur_fecha'], 'safe'],
            [['cur_nombre'], 'string', 'max' => 35],
            [['cur_idioma_id'], 'exist', 'skipOnError' => true, 'targetClass' => Idiomas::class, 'targetAttribute' => ['cur_idioma_id' => 'idi_idioma_id']],
            [['cur_nivel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Nivel::class, 'targetAttribute' => ['cur_nivel_id' => 'niv_nivel_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cur_curso_id' => 'Cur Curso ID',
            'cur_idioma_id' => 'Cur Idioma ID',
            'cur_nombre' => 'Cur Nombre',
            'cur_descripcion' => 'Cur Descripcion',
            'cur_nivel_id' => 'Cur Nivel ID',
            'cur_fecha' => 'Cur Fecha',
        ];
    }

    /**
     * Gets query for [[CurIdioma]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurIdioma()
    {
        return $this->hasOne(Idiomas::class, ['idi_idioma_id' => 'cur_idioma_id']);
    }

    /**
     * Gets query for [[CurNivel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurNivel()
    {
        return $this->hasOne(Nivel::class, ['niv_nivel_id' => 'cur_nivel_id']);
    }

    /**
     * Gets query for [[EvaluacionCursos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEvaluacionCursos()
    {
        return $this->hasMany(EvaluacionCurso::class, ['evacur_curso_id' => 'cur_curso_id']);
    }

    /**
     * Gets query for [[Temas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTemas()
    {
        return $this->hasMany(Temas::class, ['tem_curso_id' => 'cur_curso_id']);
    }
}
