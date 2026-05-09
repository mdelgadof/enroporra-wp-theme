(function () {
    'use strict';

    var cards = document.querySelectorAll('.score.live[data-fixture-id]');
    if (!cards.length) return;

    function updateCard(card, d) {
        var g1 = card.querySelector('.score-goals1 span');
        var g2 = card.querySelector('.score-goals2 span');
        var title = card.querySelector('.score-title');
        var badge = card.querySelector('.live-badge');

        if (g1 && d.goals1 !== null) g1.textContent = d.goals1;
        if (g2 && d.goals2 !== null) g2.textContent = d.goals2;

        if (d.status === 'finished') {
            if (title) title.textContent = 'Terminado';
            if (badge) badge.remove();
            card.classList.remove('live');
            card.classList.add('past');
        } else if (title && d.minute) {
            var minuteStr = d.minute === 'HT' ? 'HT' : d.minute + "'";
            title.textContent = 'En directo, ' + minuteStr;
        }
    }

    function poll() {
        var body = new URLSearchParams({ action: 'ep_live_scores' });
        fetch(enroporraVars.ajaxUrl, { method: 'POST', body: body })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                cards.forEach(function (card) {
                    var id = card.dataset.fixtureId;
                    if (data[id]) updateCard(card, data[id]);
                });
            })
            .catch(function () {
                // silences network errors; next tick will retry
            });
    }

    poll();
    setInterval(poll, 60000);
}());
