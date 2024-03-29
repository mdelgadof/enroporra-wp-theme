jQuery(document).ready(function ($) {

    // General dropdown selectors
    $('div.dropdown-launcher').on('click',function() {
        $(this).parent().children('ul.dropdown-content').toggle();
    });
    $('ul.dropdown-content li').on('click',function() {
        $(this).parent().parent().children('div.dropdown-launcher').html($(this).html());
        $(this).parent().hide();
    });

    // Bet 1 dropdown exclusive: filling "scorer_id" field in form.
    $('#betScorers li').on('click',function() {
        $('#player_id').val($(this).data('player_id'));
    });

    // Bet 2 dropdown exclusive: filling "referee_id" field in form.
    $('#betReferee li').on('click',function() {
        $('#referee_id').val($(this).data('referee_id'));
    });

    $('input[type=number].betTeamResult').on('keyup',function() {
        if ($(this).val()>15) $(this).val(15);
        if ($(this).val()<0) $(this).val(0);
        if ($(this).val().length==2 && $(this).val()[0]=="0") $(this).val($(this).val()[1]);
        if ($(this).val()>9) $(this).css('max-width','75px');
        if ($(this).val()<10) $(this).css('max-width','50px');
    });

    $('#submit_bet1_form').on('click',function(e) {
        e.preventDefault();
        if ($('#player_id').val()=='') {
            alert('¡Debes elegir un pichichi!');
            $('#player_id').focus();
            return;
        }
        if ($('input[name=enroporra_name]').val()=='') {
            alert('¡Debes rellenar el nombre del apostante!');
            $('input[name=enroporra_name]').focus();
            return;
        }
        if ($('input[name=enroporra_email]').val()!=$('input[name=enroporra_email2]').val()) {
            alert('¡Los emails no coinciden!');
            $('input[name=enroporra_email2]').focus();
            return;
        }
        $('#bet1_form').submit();
    });

    $('#submit_bet2_form').on('click',function(e) {
        e.preventDefault();
        var results_fail = false;

        // Check valid referee
        if ($('#referee_id').val()=='') {
            alert('¡Debes elegir un árbitro para la final!');
            $('#referee_id').focus();
            return;
        }

        // Check empty results
        $('#bet2_form input[type=number]').each(function() {
            if (results_fail) return;
            console.log(typeof $(this).val());
            if (typeof $(this).val()===undefined || $(this).val() == "") {
                alert('¡Te has dejado algún resultado por rellenar!');
                $(this).focus();
                results_fail = true;
            }
        });
        if (results_fail) return;

        // Check draws
        $('.bet2-matchdiv').each(function() {
            if (results_fail) return;
            var fixture_number = $(this).data('fixture-number');
            if ($('input[name='+fixture_number+'_team1result]').val()==$('input[name='+fixture_number+'_team2result]').val()) {
                if ($('input[name=winner_'+fixture_number+']').val()=="") {
                    alert('¡Te faltan unos penaltis por decidir!');
                    $('input[name='+fixture_number+'_team2result]').focus();
                    results_fail = true;
                }
            }
        });
        if (results_fail) return;

        $('#bet2_form').submit();
    });

    $('.bet2-matchdiv input').on('change',function() {
        $('#bet2-wrapper input[type=number]').each(function() {

            var team_div = $(this).parent().parent();
            var match_div = team_div.parent();

            var self_number = $('#'+team_div.attr('id')).data('team-number');
            var opponent_number = (parseInt(self_number)===1) ? 2:1;

            var self_id = $('#'+team_div.attr('id')).data('team-id');
            var opponent_id = $('#' + team_div.data('opponent-id')).data('team-id');

            var self_result = parseInt($(this).val());
            var opponent_result = parseInt($('#' + team_div.data('opponent-id') + ' .bet2-team-result input').val());
            if (isNaN(self_result) || isNaN(opponent_result)) return;

            var self_name = $('#' + team_div.attr('id') + ' .bet2-team-name').html();
            var opponent_name = $('#' + team_div.data('opponent-id') + ' .bet2-team-name').html();

            var self_little_flag = $('#' + match_div.attr('id')+' .bet2-penalties-flag-'+self_number).html();
            var opponent_little_flag = $('#' + match_div.attr('id')+' .bet2-penalties-flag-'+opponent_number).html();

            var winner_place = $('#teamdiv_W' + match_div.data('fixture-number'));
            var next_match = winner_place.parent();
            var winner_next_number = winner_place.data('team-number');

            if (self_result > opponent_result) {
                $('#' + winner_place.attr('id') + ' .bet2-team-name').html(self_name);
                $('#' + winner_place.attr('id') + ' .bet2-team-id').val(self_id);
                $('#' + winner_place.attr('id')).data('team-id',self_id);
                $('#' + match_div.attr('id') + ' .bet2-resolve-draw').removeClass('bet2-resolve-draw-flex');
                $('#' + next_match.attr('id') + ' .bet2-penalties-flag-'+winner_next_number).html(self_little_flag);
            }
            else if (self_result < opponent_result) {
                $('#' + winner_place.attr('id') + ' .bet2-team-name').html(opponent_name);
                $('#' + winner_place.attr('id') + ' .bet2-team-id').val(opponent_id);
                $('#' + winner_place.attr('id')).data('team-id',opponent_id);
                $('#' + match_div.attr('id') + ' .bet2-resolve-draw').removeClass('bet2-resolve-draw-flex');
                $('#' + next_match.attr('id') + ' .bet2-penalties-flag-'+winner_next_number).html(opponent_little_flag);
            }
            else {
                $('#' + match_div.attr('id') + ' .bet2-resolve-draw').addClass('bet2-resolve-draw-flex');
                if ($('input[name=' + match_div.data('fixture-number') +'_winner]').val()==self_number) {
                    $('#' + winner_place.attr('id') + ' .bet2-team-name').html(self_name);
                    $('#' + winner_place.attr('id') + ' .bet2-team-id').val(self_id);
                    $('#' + winner_place.attr('id')).data('team-id',self_id);
                    $('#' + next_match.attr('id') + ' .bet2-penalties-flag-'+winner_next_number).html(self_little_flag);
                }
                else if ($('input[name=' + match_div.data('fixture-number') +'_winner]').val()==opponent_number) {
                    $('#' + winner_place.attr('id') + ' .bet2-team-name').html(opponent_name);
                    $('#' + winner_place.attr('id') + ' .bet2-team-id').val(opponent_id);
                    $('#' + winner_place.attr('id')).data('team-id',opponent_id);
                    $('#' + next_match.attr('id') + ' .bet2-penalties-flag-'+winner_next_number).html(opponent_little_flag);
                }
            }
        });
    });
    $('.bet2-penalties-winner').on('click',function() {

        $(this).parent().children('.bet2-penalties-winner').html('<img src="/wp-content/themes/enroporra/images/emojis/cry.webp" style="width:20px" />');
        $(this).parent().children('.bet2-penalties-winner').css('border','0');

        $(this).html('<img src="/wp-content/themes/enroporra/images/emojis/joy.webp" style="width:20px" />');
        $(this).parent().children('input[type=hidden]').val($(this).data('winner'));

        $('.bet2-matchdiv input[type=number]').trigger( "change" );
    });
});
