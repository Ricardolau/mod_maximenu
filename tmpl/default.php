<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
/* Ejemplo de lo que llamamos Nivel:
 *   Menu1 (Nivel1)
 * 		Menu2 (Nivel2)
 * 		menu2
 * 	 Menu0  (Nivel1)
 * 		Menu2  (Nivel2)
 * 		menu2
 * */
// $item->level indica el nivel que está.
// El nivel empieza en 1 y cada vez que incrementa bajando a hijos.
// $item->level_diff indica si va a bajar o subir de Nivel... Si es negativo sube nivel y si es positivo sube nivel.
$contador=0;
// Note. It is important to remove spaces between elements.
?>
<?php // The menu class is deprecated. Use nav instead. ?>
<div class="maximenu">
<ul class="maximenu<?php echo $class_sfx;?>"<?php
	$tag = '';

	if ($params->get('tag_id') != null)
	{
		// Id que ponemos en parametros avanzados 
		$tag = $params->get('tag_id') . '';
		echo ' id="' . $tag . '"';
	}
?>>
<?php
foreach ($list as $i => &$item)
{
	$class = 'item-' . $item->id;
	$contador = $contador +1;
	if (($item->id == $active_id) OR ($item->type == 'alias' AND $item->params->get('aliasoptions') == $active_id))
	{
		$class .= ' current';
	}

	if (in_array($item->id, $path))
	{
		$class .= ' active';
	}
	elseif ($item->type == 'alias')
	{
		$aliasToId = $item->params->get('aliasoptions');

		if (count($path) > 0 && $aliasToId == $path[count($path) - 1])
		{
			$class .= ' active';
		}
		elseif (in_array($aliasToId, $path))
		{
			$class .= ' alias-parent-active';
		}
	}

	if ($item->type == 'separator')
	{
		$class .= ' divider';
	}

	if ($item->deeper)
	{
		$class .= ' deeper';
	}

	if ($item->parent)
	{
		$class .= ' parent';
	}

	if (!empty($class))
	{
		if ($item->level == 2 ){
		/* Ahora creamos variable de columnas con el valor de columnas que nos indica el array ;
		* $ItMenuNi, este array tiene como clave el id y los hijo que tiene .
		* Recuerda que utilizamos Bootstrap donde la regilla es de 12 por eso 
		* */
		$idPadre =$item->tree[0];
		if ($ItMenuNi[$idPadre]){
		$columnas =  12 / $ItMenuNi[$idPadre] ;
		$columnas =  round($columnas);
		} else {
		$columnas = 12;
		}
		$class = ' class='.'"col-md-'.$columnas.' Nivel'.$item->level.' '. trim($class) . '"';
		} else {
		$class = ' class='.'"Nivel'.$item->level.' '. trim($class) . '"';
		}
	}

	echo '<li' . $class . '>';
	

	// Render the menu item.
	switch ($item->type) :
		case 'separator':
		case 'url':
		case 'component':
		case 'heading':
			/* El siguiente condicional es para depurar debe estar comentado cuando 	;
			 * esta en producción ya que lo utilizamos para copiar un item de menu y	;
			 * luego presentar al como modo depurador y asi ver el contenido que lleva 	;
			 * da item * */
			//~ if ($contador == 4)
			//~ {
			//~ $itemnuevo = $item;
			//~ 
			//~ }
			// Fin condicional para depurar.
			require JModuleHelper::getLayoutPath('mod_menusv', 'default_' . $item->type);
			break;

		default:
			require JModuleHelper::getLayoutPath('mod_menusv', 'default_url');
			break;
	endswitch;

	/* Ahora vamos crear una variable para indicar cual es el nivel siguiente.
	 * Aqui puede darse varias situaciones que estemos en el nivel 2 y 
	 * que el siguiente $item va ser menos profundo por lo que debemos
	 * cerrar <div class="maximenu2"> y no debemos cerrar </li>
	 * Tambien puede darse que estemos un nivel superior como 3 o 4 y 
	 * el siguiente nivel sea le uno, esto lo indica la $item->level_diff
	 * que será negativo..
	 * Ejemplo:
	 * Estamos en nivel 4 y el siguiente item es del nivel 1, entonces
	 * $item_>level_diff = -3
	 * Por lo que si queremos saber si va volver al nivel 1, debemos
	 *  $item->level + $item->level_diff ( Se suma porque realmente el item
	 * es negativo. */
	
	$NivelItemSigu = $item->level - $item->level_diff ;

	if ($NivelItemSigu == $item->level)
	{
	// El siguiente es del mismo nivel
	//~ echo '</li>';
	//~ echo '<br/> Es mismo Nivel';
	$NivelItemSigu = 0; // Realmente no hay siguiente nivel , es mismo
	}
	if ($NivelItemSigu == 2 && $item->level == 1)
	{
	/*  El nivel siguiente va ser 2 el de MaxiMenu y venimos del uno
	 * por lo que sabemos que entramos, por lo que debemos abrir div maximenu2
	 * */
	 echo '<div class="maximenu2 row">';
	 
	 
	}
	if ($NivelItemSigu < $item->level && $NivelItemSigu > 0 && $NivelItemSigu !== 1)
	{
	// El siguiente item es menor al nivel y es mayor y distinto 1 
	echo '</li>';
	echo str_repeat('</ul></li>', $item->level_diff);

	}
	if ($NivelItemSigu > $item->level)
	{
	// El siguiente $item es más profundo, aumenta Nivel
	echo '<ul class="descendente'.$item->level.'">';
	}
	
	
	if ($NivelItemSigu == 1 && $item->level > 1)
	{
	/*  El nivel siguiente va ser 1 , y venimos de un nivel más grande, 
	 *  en estos momento debemos saber cual es el nivel que estamos y la 
	 *  diferencia hasta 1, ya que tenemos que cerrar las etiquetas:
	 * 	</li> y </ul> y cerrar el </div>.
	 * */
	 
	//~ echo str_repeat('</li></ul>', $item->level_diff);
	echo str_repeat('</li></ul>', $item->level_diff);
	echo '</div><!-- Entro alli-->';
	echo '</li>';
	//~ echo '</li>';

	}

	
	
	
	
	
} // Cierre foreach
?></ul>

</div>


<?php // Depurardor.... 
			
			//~ echo '<div class="separador"></div><div>';
			//~ echo '<pre>';
			//~ print_r($ItMenuNi);
			//~ echo '</pre>';
			//~ echo '</div>';
			//~ echo '<div class="separador"></div><div>';
			//~ echo '<pre>';
			//~ print_r($itemnuevo);
			//~ echo '</pre>';
			//~ echo '</div>';
			
?>
