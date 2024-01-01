document.addEventListener(
    'DOMContentLoaded', () => {
        const switcher = document.getElementById('csvm-execution-type');
        const numberOfRows = document.getElementById('csvm-number-of-rows');
        switcher.addEventListener(
        'change', () => {
                if(switcher.value === 'wp-cron' ) {
                    if(numberOfRows.classList.contains('csvm-d-none') ) {
                        numberOfRows.classList.remove('csvm-d-none');
                    }
                }else{
                if(! numberOfRows.classList.contains('csvm-d-none') ) {
                    numberOfRows.classList.add('csvm-d-none');
                }
                }
                } 
    );
    } 
);