var confirmDeleteModal = $('#modal-delete');
var tblObj = $("#list-table");
var sort_by = $('.sortable.selected').data('sort');
var sort_dir = 'desc';
var mediaModelView = $('#media-img-view');
var listTable = function(){
    var filterData = serializeToJson($('#filter_row :input').serializeArray());
    filterData.sync = 1;
    filterData.sort_by = sort_by;
    filterData.sort_dir = sort_dir;
  $.ajax({
    url: tblObj.data('url'),
    type : 'GET',
    data : filterData,
    dataType: 'json'
  })
  .done(function(response) {
      tblObj.find('tbody').html(response.rows);
      tblObj.find('tfoot tr td').html(response.pagination);
      if($('#total-results').length>0) {
        $('#total-results').text(response.total);
      }
      resetSort();
      reinitPagination();
      reinitImageView();
  })
  .fail(function(response, code) {
      toastr.error('Error in listing rows');
  });
};

listTable();

var reinitPagination = function(){
  $('.pagination a').on('click', function(e){
      e.preventDefault();
      tblObj.data('url', $(this).attr('href'));
      if(typeof paginationPage !== "undefined"){
        let paginationPageUrl=$(this).attr('href');
        let paginationPageUrlArray=paginationPageUrl.split("?page=");
        paginationPage=paginationPageUrlArray[1];
        console.log(paginationPage);
      }
      listTable();
  });
}
var deleteId = 0;
var deleteRecord = function(id){
console.log('hh')
     deleteId  = id;
     confirmDeleteModal.modal('show');
}
var confirmDelete = function(){
  console.log('h')
   $.ajax({
      url: deleteUrl,
      type : 'DELETE',
      data : {id:deleteId, _token:window.Laravel.csrfToken},
      dataType: 'json'
    })
    .done(function(response) {
      toastr.success(response.message);
        listTable();
        deleteId= 0;
        confirmDeleteModal.modal('hide');
    })
    .fail(function(error, code) {
      if(error.message){
        toastr.error(error.message);
      }else if(error.responseJSON){
        toastr.error(error.responseJSON.message);
      }
    });
}

var cloneRecord = function(cloneUrl, type=''){
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
        listTable();
    })
    .fail(function(response, code) {
        toastr.error(response.message);
    });
}

function serializeToJson(serializer){
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

$('.sortable').on('click', function(){
    if($('.sortable').hasClass('selected')) {
        sort_dir = sort_dir==='desc' ? 'asc' : 'desc';
    }
    sort_by = $(this).data('sort');
    listTable();
});

var resetSort = function() {
    $('.sortable').removeClass('selected');
    $('.sortable i').removeClass('icon-arrow-down');
    $('.sortable i').removeClass('icon-arrow-up');
    var sortelm = $('.sortable[data-sort="'+sort_by+'"]');
    sortelm.addClass('selected');
    sortelm.find('i').addClass(sort_dir==='desc'?'icon-arrow-down':'icon-arrow-up');
}

var resetFilter = function() {
    $('#filter_row :input').val('');
    listTable();
}

var reinitImageView = function() {
  console.log('test');
  $('.img-view').on('click', function(){
    var img =  $(this).data('img');
    mediaModelView.find('img').attr('src', img);
    mediaModelView.modal('show');
 });
}

var filterResults = function(url){
    tblObj.data('url', url);
    listTable();
}



// var close = $('.close-search');
// var overlay = $('.filter-backdrop');
// search.click(function() {
//   filter_show();
// });
// close.click(function() {
//   filter_hide();
// });
//
// function filter_show() {
//   $('#filters').addClass('filtered');
//   overlay.show(20);
// }
//
// function filter_hide() {
//   $('#filters').removeClass('filtered');
//   overlay.hide();
// }

function exportCsv(that, url){
  toastr.success("Please wait for data export....");
  $(that).prop("disabled", true).text("Exporting..");
  var filterData = serializeToJson($('#filter_row :input').serializeArray());
  filterData.sync = 1;
  filterData.sort_by = sort_by;
  filterData.sort_dir = sort_dir;
  $.ajax({
    url: url,
    type : 'GET',
    data : filterData,
    dataType: 'json',
    timeout: 180000,
    success: function (response){
      toastr.success(response.message);
      let redirectTo = response.redirectTo;
      if(redirectTo){
        window.location.href = redirectTo;
      }
      $(that).prop("disabled", false).text("Export");
    },
    error: function (error){
      $(that).prop("disabled", false).text("Export");
      let errors = error.responseJSON.errors;
      if(errors){
        for (var key in errors){
          toastr.error(errors[key][0]);
        }
      }else{
        let message = error.responseJSON.message;
        if(message){
          toastr.error(message);
        }
      }
    }
  });
}
