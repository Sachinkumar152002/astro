var confirmDeleteModalSecond = $('#modal-delete-second');
var tblObjSecond = $("#list-table-second");
var sort_by_second = $('.sortable-second.selected-second').data('sort');
var sort_dir_second = 'desc';
var mediaModelViewSecond = $('#media-img-view-second');
var listTableSecond = function(){
  console.log(tblObjSecond.data('url'))
    var filterData = serializeToJson($('#filter_row_second :input').serializeArray());
    filterData.sync = 1;
    filterData.sort_by = sort_by;
    filterData.sort_dir = sort_dir;
  $.ajax({
    url: tblObjSecond.data('url'),
    type : 'GET',
    data : filterData,
    dataType: 'json'
  })
  .done(function(response) {
      tblObjSecond.find('tbody').html(response.rows);
      tblObjSecond.find('tfoot tr td').html(response.pagination);
      if($('#total-results-second').length>0) {
        $('#total-results-second').text(response.total);
      }
      resetSortSecond();
      reinitPaginationSecond();
      reinitImageViewSecond();
  })
  .fail(function(response, code) {
      toastr.error('Error in listing rows');
  });
};

listTableSecond();

var reinitPaginationSecond = function(){
  $('.pagination-second a').on('click', function(e){
      e.preventDefault();
      tblObjSecond.data('url', $(this).attr('href'));
      if(typeof paginationPageSecond !== "undefined"){
        let paginationPageUrl=$(this).attr('href');
        let paginationPageUrlArray=paginationPageUrl.split("?page=");
        paginationPageSecond=paginationPageUrlArray[1];
        console.log(paginationPageSecond);
      }
      listTableSecond();
  });
}
var deleteIdSecond = 0;
var deleteRecordSecond = function(id){
console.log('hh')
     deleteIdSecond  = id;
     confirmDeleteModalSecond.modal('show');
}
var confirmDeleteSecond = function(){
  console.log('h')
   $.ajax({
      url: deleteUrlSecond,
      type : 'DELETE',
      data : {id:deleteIdSecond, _token:window.Laravel.csrfToken},
      dataType: 'json'
    })
    .done(function(response) {
      toastr.success(response.message);
        listTableSecond();
        deleteIdSecond= 0;
        confirmDeleteModalSecond.modal('hide');
    })
    .fail(function(error, code) {
      if(error.message){
        toastr.error(error.message);
      }else if(error.responseJSON){
        toastr.error(error.responseJSON.message);
      }
    });
}

var cloneRecordSecond = function(cloneUrl, type=''){
   $.ajax({
      url: cloneUrl,
      type : 'GET',
      dataType: 'json',
      data:{
        type:type
      }
    })
    .done(function(response) {
        toastr.success(response.message);
        listTableSecond();
    })
    .fail(function(response, code) {
        toastr.error(response.message);
    });
}

function serializeToJsonSecond(serializer){
    var _string = '{';
    for(var ix in serializer)
    {
        var row = serializer[ix];
        _string += '"' + row.name + '":"' + row.value + '",';
    }
    var end =_string.length - 1;
    _string = _string.substr(0, end);
    _string += '}';

    return JSON.parse(_string);
}

$('.sortable-second').on('click', function(){
    if($('.sortable-second').hasClass('selected')) {
        sort_dir = sort_dir==='desc' ? 'asc' : 'desc';
    }
    sort_by = $(this).data('sort');
    listTableSecond();
});

var resetSortSecond = function() {
    $('.sortable-second').removeClass('selected');
    $('.sortable-second i').removeClass('icon-arrow-down');
    $('.sortable-second i').removeClass('icon-arrow-up');
    var sortelm = $('.sortable-second[data-sort="'+sort_by+'"]');
    sortelm.addClass('selected');
    sortelm.find('i').addClass(sort_dir==='desc'?'icon-arrow-down':'icon-arrow-up');
}

var resetFilterSecond = function() {
    $('#filter_row_second :input').val('');
    listTableSecond();
}

var reinitImageViewSecond = function() {
  console.log('test');
  $('.img-view-second').on('click', function(){
    var img =  $(this).data('img');
    mediaModelViewSecond.find('img').attr('src', img);
    mediaModelViewSecond.modal('show');
 });
}

var filterResultsSecond = function(url){
    tblObjSecond.data('url', url);
    listTableSecond();
}
