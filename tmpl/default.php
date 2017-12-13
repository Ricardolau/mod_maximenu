<?php
/**
 * Origin @package     Joomla.Site
 * Origin @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @ version   1.0
 * @ co-autor : Ricardo Carpintero
 * @ ayuda: info@solucionesvigo.es
 */

defined('_JEXEC') or die;

$id = '';
$estado_maximenu= '';
$contador_hermano = 0;
if ($tagId = $params->get('tag_id', ''))
{
	$id = ' id="' . $tagId . '"';
}

// The menu class is deprecated. Use nav instead
?>
<div class="menuSv">
<ul class="menu<?php echo $class_sfx; ?>"<?php echo $id; ?>>
<?php 
	// Hay que recordar que dentro cada item tenemos parametros,
	// puede haber uno del plugin menusv (pmenusv) donde puede :
	//  valor -> 1 ; que es maximenu 
	//  valor -> 0 ; No es maximenu , pero antes era maximenu.
	// 	NO EXISTE -> O que no esta instalado el plugin o que no puso valor nunca
	
	//~ echo '<pre>';
	//~ print_r($list);
	//~ echo '</pre>';
?>
<?php foreach ($list as $i => &$item)
{
	$class = 'Nivel'.$item->level.' item-' . $item->id;

	if ($item->id == $default_id)
	{
		$class .= ' default';
	}

	if ($item->id == $active_id || ($item->type === 'alias' && $item->params->get('aliasoptions') == $active_id))
	{
		$class .= ' current';
	}

	if (in_array($item->id, $path))
	{
		$class .= ' active';
	}
	elseif ($item->type === 'alias')
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

	if ($item->type === 'separator')
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
	if ($estado_maximenu === 'activo'){
		if ( $padre_maxi == $item->parent_id){
			// Ahora ponemos etiqueta a hijos si esta maxi
			// Ademas de iniciar o sumar al contador hermanos(hijos)
			if ($contador_hermano === 0){
				$hermanos = $item->hermanos;
				$contador_hermano = 1;
				
			} else {
				$contador_hermano++;
			}
		// Calculo de numero de columnas según hermanos
		$num_columnas = (int)(12/$hermanos);
		if ($num_columnas <2){
			$num_columnas = 2; // No permitimos columnas inferiores a dos,... :-)
		}
		$class_div = 'col-md-'.$num_columnas.' Column-Maxi Hijo Maxi_'.$contador_hermano.' deeper_'.$item->deeper;
		echo '<div class="'.$class_div.'">';
		$class_ul = 'maxi descendente'.$item->level.' id-'.$item->id;
		echo '<ul class="'.$class_ul.'">';	
		}
	}
	
	// Identificamos si es un nieto
	// Una forma practica de identificar si es un nieto, lo que hacemos utilizar el nivel 3 como tal..
	if ($item->level === "3" && $estado_maximenu = 'activo'){
		// Es un nieto
		//~ $class_ul = 'maxi1 descendente'.$item->level.' id-'.$item->id;
		//~ echo '<ul class="'.$class_ul.'">';	
		// Clase para li
		$class .= ' Nieto Maxi'.$hermanos.'-'.$contador_hermano;	
	}
	
	
	
	
	echo '<li class="' . $class . '">';

	switch ($item->type) :
		case 'separator':
		case 'component':
		case 'heading':
		case 'url':
			require JModuleHelper::getLayoutPath('mod_menusv', 'default_' . $item->type);
			break;

		default:
			require JModuleHelper::getLayoutPath('mod_menusv', 'default_url');
			break;
	endswitch;

	// El proximo item es hijo.

	if ($item->deeper)
	{
		$class_ul = 'descendente'.$item->level.' id-'.$item->id;
		if ($item->params->get('pmenusv_maxi')=== "1" && $item->level === "1"){
			 // Si el parametro item menu tiene activado maximenu
			 // recuerda que solo permito maximenu en nivel 1.
			 $estado_maximenu = 'activo';
			 $padre_maxi  = $item->id;
			 echo '<div class="maximenu2 row">';
		} else {
			if ($estado_maximenu === 'activo'){
				$class_ul .= ' maxi';
			}
			echo '<!-- Entro en ul en item_id:'.$item->id. ' -->';
			echo '<ul class="'.$class_ul.'">'; 

		}
	
	}	else {
		$cierre_etiquetas = '</li>';

		if ($item->shallower)
		{
			// El proximo es menos profundo.

			if ($estado_maximenu === 'activo') {
				$diferencia = $item->level_diff ;
				// Identificamos si es un nieto o no 
				if ($item->level === "3"){
					// Es nieto por lo que cerramos tb <ul>
					$cierre_etiquetas .= '</ul></li>';
				}
				// Si es nieto o hijo, va descender.. 
				// Cerramos div columna
				$cierre_etiquetas .= '</ul></div>';
				if ($hermanos === $contador_hermano){
					// Es el ultimo hijo o el ultimo nieto del ultimo hijo y va descender
					$estado_maximenu = '';
					$hermanos = 0;
					$contador_hermano = 0;
					// Cerramos etiqueta div de maxi menu
					$cierre_etiquetas .= '</div>';

				}
				echo $cierre_etiquetas;
				
			} else {
				
				//~ $cierre_etiquetas .= str_repeat('</ul></li>', $item->level_diff);
				$cierre_etiquetas .='</ul></li>';

				echo $cierre_etiquetas;
			}
			echo '<!-- '.$cierre_etiquetas.'-->';

		
		}	else {
		// El siguiente item es un hermano.
			if ($estado_maximenu === 'activo' && $item->level === "2") {
			// Es hijo que no tiene nietos.
			$cierre_etiquetas .= '</ul></div><!-- Entro shallower div columna mismo nivel-->';
			}
		echo $cierre_etiquetas;
		}
	}
}
?>
</ul>
</div>
