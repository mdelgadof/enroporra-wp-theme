<?php
/**
 * Overlay temporal segunda fase Mundial 2026.
 * Visible hasta las 18:30 UTC del 28-06-2026 (30 min antes del primer last32).
 * Se incluye en page-clasificacion.php y page-clasificacion-amigos.php.
 */
?>
<div id="playoff-overlay" class="playoff-overlay">
    <h3><?php _e('¡Este año tenemos menos tiempo!', 'enroporra') ?></h3>
    <p><?php _e('El Mundial 2026 termina ya su primera fase. El último partido de la fase de grupos termina esta noche más o menos a las 6AM hora española (5AM en Canarias, 12AM en EEUU) y es entonces cuando sabremos todos los cruces y podremos empezar a rellenar la apuesta de la segunda fase. Pero los dieciseisavos también arrancan mañana mismo, 28 de junio a las 9PM (8PM Canarias, 3PM en EE.UU.). Son solo 14 horas y media para rellenar la segunda fase, así que estad atentos y rellenad mañana domingo sin falta vuestra apuesta, ¡queda todo por decidir!', 'enroporra') ?></p>
    <p><?php _e('Lo intentamos negociar con la FIFA. Les enviamos un dossier, reservamos una sala de conferencias y hasta les preparamos un PowerPoint con transiciones muy chulas. Nos dijeron que el formato ya estaba decidido y que, con todo el respeto, no éramos exactamente su audiencia prioritaria.', 'enroporra') ?></p>
    <p><strong><?php _e('Os recordamos cómo puntúan los dieciseisavos:', 'enroporra') ?></strong></p>
    <ul>
        <li><?php _e('1 punto por acertar el equipo ganador', 'enroporra') ?></li>
        <li><?php _e('+1,5 puntos extra si además aciertas el resultado exacto', 'enroporra') ?></li>
    </ul>
    <p><?php _e('Es decir, como en la fase de grupos. El resto de rondas puntúan igual que otros años.', 'enroporra') ?></p>
    <p><strong><?php _e('Y al final del torneo, recuerda los bonos de esta edición:', 'enroporra') ?></strong></p>
    <ul>
        <li><?php _e('+5 puntos por haber acertado el árbitro de la final', 'enroporra') ?></li>
        <li><?php _e('+7,5 puntos extra si tu pichichi resulta ser el máximo goleador del Mundial', 'enroporra') ?></li>
    </ul>
    <p><?php
        /* translators: %s = URL to the rules page */
        $rules_post_id = get_post_meta( $GLOBALS['ep_competition']->ID, 'rules', true );
        printf( __( 'Consulta las <a href="%s">bases completas aquí</a>.', 'enroporra' ), esc_url( get_permalink( $rules_post_id ) ) );
    ?></p>
    <div class="playoff-overlay__btn-wrap">
        <button class="playoff-overlay__btn" onclick="dismissPlayoffOverlay()"><?php _e('Entendido', 'enroporra') ?></button>
    </div>
</div>
