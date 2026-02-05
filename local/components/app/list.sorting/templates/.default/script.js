(function() {
    ['complete','interactive'].includes(document.readyState)?init():document.addEventListener('DOMContentLoaded', init);

    function run() {
        let form = document.forms.sortingTypes;

        if (form && form.list_de62) {
            form.list_de62.addEventListener('change', function() {
                if (!this.selectedOptions.length) {
                    return false;
                }

                let params = {
                    mode: 'class',
                    data: {query: this.selectedOptions[0].value},
                    signedParameters: BX.message('SORTING_PARAMETERS')
                };

                BX.ajax.runComponentAction(BX.message('SORTING_COMPONENT'), 'changeSorting', params)
                    .then(() => {/* some actions that use new sorting order */ location.reload()})
                    .catch((resp) => {console.dir(resp)});
            });
        }
    }

    function init() {
        const id=setInterval(function(){if(typeof window.BX==='function'){clearInterval(id);run()}},200);
    }
})();
