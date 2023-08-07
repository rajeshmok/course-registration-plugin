var course_code = [];
(function ($) {
    $('.progresswrapper').contents().filter(function () { return this.nodeType === 3; }).remove();

    $('#courses_code').select2(
        {
            placeholder: 'Selecteer een cursus'
        }
    );

    $('#courses_code').on('select2:select', function (e) {
        $('input.select2-search__field').prop('placeholder', 'Klik voor nog een cursus');
        $('input.select2-search__field').attr('style', 'width: 100%')
    });


    $('#courses_code_head').on('select2:select', function (e) {
        $('input.select2-search__field').prop('placeholder', 'Klik voor nog een cursus');
        $('input.select2-search__field').attr('style', 'width: 100%')
    });

    $('#courses_code_head_niwo').on('select2:select', function (e) {
        $('input.select2-search__field').prop('placeholder', 'Klik voor nog een cursus');
        $('input.select2-search__field').attr('style', 'width: 100%')
    });


    $('#referral').select2({
        minimumResultsForSearch: -1,
        placeholder: ""
    });

    $('#courses_code_head').select2({
        minimumResultsForSearch: -1,
        placeholder: "Selecteer een cursus",
    });

    $('#courses_code_head_niwo').select2({
        minimumResultsForSearch: -1,
        placeholder: "Selecteer een cursus",
    });

    $('#courses_location_head').select2({
        minimumResultsForSearch: -1
    });

    $('#courses_location_head_niwo').select2({
        minimumResultsForSearch: -1
    });
});