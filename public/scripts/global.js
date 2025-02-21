$(() => {
  $('[data-expand]').on('click', function (e) {
    $(this).children('img').toggleClass('-rotate-180')
    let target = $(`#${$(this).data('expand')}`)
    target.slideToggle()
  })

  // Toggle manual payment details
  $('input[name="method"]').on('change', function () {
    let target = $('#manualPaymentDetail');
    if ($(this).is(':checked') && $(this).val() == 'transfer') {
        target.show();  // Menampilkan input bank jika memilih transfer
    } else {
        target.hide();  // Menyembunyikan input bank jika memilih cash
    }
});

})

