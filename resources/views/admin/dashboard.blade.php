@extends('layouts.admin')
@push("styles")
<style>
  .dashboard {
    --dashboard-purple: #674181;
    --dashboard-gold: #bd8524;
  }
  .dashboard > .gutters-1 { row-gap: 12px; }
  .dashboard > .gutters-1 > .col-md-2.col-6 .card {
    position: relative;
    min-height: 132px;
    overflow: hidden;
    border: 1px solid #ece4f2 !important;
    border-radius: 14px !important;
    background: linear-gradient(145deg, #f4edf8 0%, #fff 88%) !important;
    box-shadow: 0 4px 14px rgba(55, 35, 75, .07);
    transition: transform .2s ease, box-shadow .2s ease;
  }
  .dashboard > .gutters-1 > .col-md-2.col-6:nth-child(4n + 2) .card,
  .dashboard > .gutters-1 > .col-md-2.col-6:nth-child(4n + 3) .card {
    border-color: #f0e5d1 !important;
    background: linear-gradient(145deg, #fff4e2 0%, #fff 88%) !important;
  }
  .dashboard > .gutters-1 > .col-md-2.col-6 .card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 24px rgba(55, 35, 75, .12);
  }
  .dashboard > .gutters-1 > .col-md-2.col-6 .card > div:first-child {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    margin-bottom: 10px;
    border-radius: 12px;
    background: rgba(255, 255, 255, .82);
    box-shadow: 0 2px 7px rgba(55, 35, 75, .07);
  }
  .dashboard > .gutters-1 > .col-md-2.col-6 .card svg,
  .dashboard > .gutters-1 > .col-md-2.col-6 .card .bi { color: var(--dashboard-purple) !important; }
  .dashboard > .gutters-1 > .col-md-2.col-6:nth-child(4n + 2) .card svg,
  .dashboard > .gutters-1 > .col-md-2.col-6:nth-child(4n + 2) .card .bi,
  .dashboard > .gutters-1 > .col-md-2.col-6:nth-child(4n + 3) .card svg,
  .dashboard > .gutters-1 > .col-md-2.col-6:nth-child(4n + 3) .card .bi { color: var(--dashboard-gold) !important; }
  .dashboard > .gutters-1 > .col-md-2.col-6 .card h4 { margin-bottom: 3px!important; color: #2c2038; font-size: 25px; font-weight: 700; line-height: 1.1; }
  .dashboard > .gutters-1 > .col-md-2.col-6 .card p { color: #655a70; font-size: 13px; font-weight: 500; line-height: 1.4; }
  .admin-users{
    display: flex;
    gap : 5px;
    padding-top: 4px;
  }
 </style>
@endpush

@section('content')

      <div class="dashboard">
        <!-- education-box-start -->
        <div class="row gutters-1">
          <!-- Download Transaction Reports Card -->
          <div class="col-md-12">
            <div class="px-2">
              <div class="d-flex justify-content-between">

                @if($astrologerIllegalChatAppointmentsCount>0)
                  <div class="">

                    <div class="btn btn-primary d-flex justify-content-between align-items-center p-left">
                      <p class="small mb-0 text-white">These chat contains instances of illegal or prohibited activity <span class="blink bg-danger text-white p-1 rounded-circle">{{$astrologerIllegalChatAppointmentsCount}}</span></p>
                      <button class="btn btn-default   k_view_all k2">
                        <a href="{{ route('admin.consultation.chat.index',['Astrologer','has_illegal_activity'=>'yes']) }}" target="_blank" class="text-white">View All <i class="bi bi-caret-down-fill k_down"></i></a>
                      </button>
                    </div>

                  </div>
                @endif

                {{--<a href="{{ route('admin.transaction.report.download') }}" target="_blank" ><button type="button" name="button" class="btn btn-primary btn-report fs-12" >
                  <i class="bi bi-download"></i> Download Transaction Reports</button>
                </a>--}}
                <div></div>
              </div>
            </div>
          </div>



          <div class="col-md-2 col-6">
            <a href="{{route('admin.consultation.chat.index', ['Astrologer'])}}">
              <div class="card p-3">
              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-chat-right-dots" viewBox="0 0 16 16">
                <path d="M2 1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h9.586a2 2 0 0 1 1.414.586l2 2V2a1 1 0 0 0-1-1zm12-1a2 2 0 0 1 2 2v12.793a.5.5 0 0 1-.854.353l-2.853-2.853a1 1 0 0 0-.707-.293H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2z"/>
                <path d="M5 6a1 1 0 1 1-2 0 1 1 0 0 1 2 0m4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0m4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
              </svg>
              </div>
                <h4 class="mb-0">{{ $astrologerAppointments->where("package", 2)->count() }} </h4 class>
                <p class="small mb-0">Chats</p>
              </div>
            </a>
          </div>
          <div class="col-md-2 col-6">
            <a href="{{route('admin.consultation.call.index', ['Astrologer'])}}">
            <div class="card p-3">
              <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-telephone-outbound" viewBox="0 0 16 16">
  <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608 17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.68.68 0 0 0-.58-.122l-2.19.547a1.75 1.75 0 0 1-1.657-.459L5.482 8.062a1.75 1.75 0 0 1-.46-1.657l.548-2.19a.68.68 0 0 0-.122-.58zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877zM11 .5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V1.707l-4.146 4.147a.5.5 0 0 1-.708-.708L14.293 1H11.5a.5.5 0 0 1-.5-.5"/>
</svg></div>
              <h4 class="mb-0">{{ $astrologerAppointments->where("package", 3)->count() }} </h4>
              <p class="small mb-0">Calls  </p>
            </div>
          </a>
          </div>
          <div class="col-md-2 col-6">
          <a href="{{route('admin.consultation.video.index', ['Astrologer'])}}">
            <div class="card p-3">
              <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-camera-video" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M0 5a2 2 0 0 1 2-2h7.5a2 2 0 0 1 1.983 1.738l3.11-1.382A1 1 0 0 1 16 4.269v7.462a1 1 0 0 1-1.406.913l-3.111-1.382A2 2 0 0 1 9.5 13H2a2 2 0 0 1-2-2zm11.5 5.175 3.5 1.556V4.269l-3.5 1.556zM2 4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h7.5a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1z"/>
    </svg>
              </div>
              <h4 class="mb-0">{{ $astrologerAppointments->where("package", 1)->count() }} </h4>

              <p class="small mb-0">Videos</p>
            </div>
          </a>
          </div>
          <div class="col-md-2 col-6">
            <a href="{{route('admin.aiconsultation.chat.index', ['AI'])}}">
            <div class="card p-3">
              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-robot" viewBox="0 0 16 16">
  <path d="M6 12.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5M3 8.062C3 6.76 4.235 5.765 5.53 5.886a26.6 26.6 0 0 0 4.94 0C11.765 5.765 13 6.76 13 8.062v1.157a.93.93 0 0 1-.765.935c-.845.147-2.34.346-4.235.346s-3.39-.2-4.235-.346A.93.93 0 0 1 3 9.219zm4.542-.827a.25.25 0 0 0-.217.068l-.92.9a25 25 0 0 1-1.871-.183.25.25 0 0 0-.068.495c.55.076 1.232.149 2.02.193a.25.25 0 0 0 .189-.071l.754-.736.847 1.71a.25.25 0 0 0 .404.062l.932-.97a25 25 0 0 0 1.922-.188.25.25 0 0 0-.068-.495c-.538.074-1.207.145-1.98.189a.25.25 0 0 0-.166.076l-.754.785-.842-1.7a.25.25 0 0 0-.182-.135"/>
  <path d="M8.5 1.866a1 1 0 1 0-1 0V3h-2A4.5 4.5 0 0 0 1 7.5V8a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1v1a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-1a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1v-.5A4.5 4.5 0 0 0 10.5 3h-2zM14 7.5V13a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V7.5A3.5 3.5 0 0 1 5.5 4h5A3.5 3.5 0 0 1 14 7.5"/>
</svg>
              </div>
              <h4 class="mb-0">{{ $aiAstrologerAppointments->count() }} </h4>

              <p class="small mb-0">AI Chats</p>
            </div>
            </a>
          </div>
          <div class="col-md-2 col-6">
            <div class="card p-3">
              <a href="{{ route('admin.order') }}">
              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-cart2" viewBox="0 0 16 16">
  <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5M3.14 5l1.25 5h8.22l1.25-5zM5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0m9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0"/>
</svg>
              </div>
              <h4 class="mb-0">{{ $sold_products->count() }} </h4>

              <p class="small mb-0">Products Orders  </p>
            </div>
          </a>
          </div>
          <div class="col-md-2 col-6">
            <a href="{{ route('admin.order') }}">
            <div class="card p-3">
              <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-sun" viewBox="0 0 16 16">
  <path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6m0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8M8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0m0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13m8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5M3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8m10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0m-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0m9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707M4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708"/>
</svg></div>
              <h4 class="mb-0">{{ $sold_poojas->count() }} </h4>
              <p class="small mb-0">Puja Orders</p>
            </div>
            </a>
          </div>
          <div class="col-md-2 col-6">
            <div class="card p-3">
              <a href="{{ route('admin.order') }}">
              <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark-richtext" viewBox="0 0 16 16">
  <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5z"/>
  <path d="M4.5 12.5A.5.5 0 0 1 5 12h3a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5m0-2A.5.5 0 0 1 5 10h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5m1.639-3.708 1.33.886 1.854-1.855a.25.25 0 0 1 .289-.047l1.888.974V8.5a.5.5 0 0 1-.5.5H5a.5.5 0 0 1-.5-.5V8s1.54-1.274 1.639-1.208M6.25 6a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5"/>
</svg></div>
              <h4 class="mb-0">{{ $sold_services->count() }} </h4>

              <p class="small mb-0">Reports Orders</p>
            </div>
            </a>
          </div>
          <div class="col-md-2 col-6">
            <a href="{{ route('admin.customer.cartitem', [0]) }}">
            <div class="card p-3">
              <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-cart-plus" viewBox="0 0 16 16">
  <path d="M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9z"/>
  <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zm3.915 10L3.102 4h10.796l-1.313 7zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
</svg></div>
              <h4 class="mb-0">{{ $cartItems->count() }} </h4>

              <p class="small mb-0"> Abandoned Cart</p>
            </div>
            </a>
          </div>
          <div class="col-md-2 col-6">
            <a href="{{route('admin.Astrologer.index')}}">
            <div class="card p-3">
              <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-lock" viewBox="0 0 16 16">
  <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m0 5.996V14H3s-1 0-1-1 1-4 6-4q.845.002 1.544.107a4.5 4.5 0 0 0-.803.918A11 11 0 0 0 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664zM9 13a1 1 0 0 1 1-1v-1a2 2 0 1 1 4 0v1a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1zm3-3a1 1 0 0 0-1 1v1h2v-1a1 1 0 0 0-1-1"/>
</svg></div>
              <h4 class="mb-0">{{ $astrologers->count() }} </h4>

              <p class="small mb-0">Total Astrologers</p>
            </div>
            </a>
          </div>
          <div class="col-md-2 col-6">
            <a href="{{route('admin.AI.index')}}">
            <div class="card p-3">
              <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-gear" viewBox="0 0 16 16">
  <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m.256 7a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1zm3.63-4.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0"/>
</svg></div>
              <h4 class="mb-0">{{ $aiAstrologers->count() }} </h4>
              <p class="small mb-0">Total AI Astrologers</p>
            </div>
          </a>
          </div>
          <div class="col-md-2 col-6">
            <a href="{{ route('admin.Pandit.index') }}">
            <div class="card p-3">
                   <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-gear" viewBox="0 0 16 16">
  <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m.256 7a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1zm3.63-4.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0"/>
</svg></div>
              <h4 class="mb-0">{{ $pandits->count() }} </h4>
              <p class="small mb-0">Total Pandit Ji</p>
            </div>
          </a>
          </div>
              <div class="col-md-2 col-6">
            <a href="{{ route('admin.Astrologer.activeList') }}">
            <div class="card p-3">
              <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-broadcast" viewBox="0 0 16 16">
  <path d="M3.05 3.05a7 7 0 0 0 0 9.9.5.5 0 0 1-.707.707 8 8 0 0 1 0-11.314.5.5 0 0 1 .707.707m2.122 2.122a4 4 0 0 0 0 5.656.5.5 0 1 1-.708.708 5 5 0 0 1 0-7.072.5.5 0 0 1 .708.708m5.656-.708a.5.5 0 0 1 .708 0 5 5 0 0 1 0 7.072.5.5 0 1 1-.708-.708 4 4 0 0 0 0-5.656.5.5 0 0 1 0-.708m2.122-2.12a.5.5 0 0 1 .707 0 8 8 0 0 1 0 11.313.5.5 0 0 1-.707-.707 7 7 0 0 0 0-9.9.5.5 0 0 1 0-.707zM10 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0"/>
</svg></div>
              <h4 class="mb-0">{{ $activeHumenAstrologers->count() }} </h4>
              <p class="small mb-0">Active Astrologers</p>
            </div>
            </a>
          </div>
          <div class="col-md-2 col-6">
            <a href="{{ route('admin.AI.activeList') }}">
            <div class="card p-3">
              <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-broadcast" viewBox="0 0 16 16">
  <path d="M3.05 3.05a7 7 0 0 0 0 9.9.5.5 0 0 1-.707.707 8 8 0 0 1 0-11.314.5.5 0 0 1 .707.707m2.122 2.122a4 4 0 0 0 0 5.656.5.5 0 1 1-.708.708 5 5 0 0 1 0-7.072.5.5 0 0 1 .708.708m5.656-.708a.5.5 0 0 1 .708 0 5 5 0 0 1 0 7.072.5.5 0 1 1-.708-.708 4 4 0 0 0 0-5.656.5.5 0 0 1 0-.708m2.122-2.12a.5.5 0 0 1 .707 0 8 8 0 0 1 0 11.313.5.5 0 0 1-.707-.707 7 7 0 0 0 0-9.9.5.5 0 0 1 0-.707zM10 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0"/>
</svg></div>
              <h4 class="mb-0">{{ $activeAiAstrologers->count() }} </h4>
              <p class="small mb-0">Active AI Astrologers </p>
            </div>
            </a>
          </div>

          <div class="col-md-2 col-6">

            <a href="{{ route('admin.Astrologer.newRequestsList') }}">
            <div class="card p-3">
                      <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-add" viewBox="0 0 16 16">
  <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/>
  <path d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z"/>
</svg></div>
              <h4 class="mb-0">{{ $newAstrologers->count() }} </h4>
              <p class="small mb-0">New Astrologer Requests</p>
            </div>
            </a>
          </div>
          <div class="col-md-2 col-6">
            <a href="{{ route('admin.consultation.chat.index', ['Astrologer']) }}">
            <div class="card p-3">
              <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-currency-rupee" viewBox="0 0 16 16">
  <path d="M4 3.06h2.726c1.22 0 2.12.575 2.325 1.724H4v1.051h5.051C8.855 7.001 8 7.558 6.788 7.558H4v1.317L8.437 14h2.11L6.095 8.884h.855c2.316-.018 3.465-1.476 3.688-3.049H12V4.784h-1.345c-.08-.778-.357-1.335-.793-1.732H12V2H4z"/>
</svg></div>
              <h4 class="mb-0">{{ $astrologerAppointments->where("package", 2)->sum("total_price") - $astrologerAppointments->where("package", 2)->sum("discount") }} </h4>
              <p class="small mb-0">Chat Revenue</p>
            </div>
            </a>
          </div>
          <div class="col-md-2 col-6">
            <a href="{{ route('admin.consultation.call.index', ['Astrologer']) }}">
            <div class="card p-3">
              <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-currency-rupee" viewBox="0 0 16 16">
  <path d="M4 3.06h2.726c1.22 0 2.12.575 2.325 1.724H4v1.051h5.051C8.855 7.001 8 7.558 6.788 7.558H4v1.317L8.437 14h2.11L6.095 8.884h.855c2.316-.018 3.465-1.476 3.688-3.049H12V4.784h-1.345c-.08-.778-.357-1.335-.793-1.732H12V2H4z"/>
</svg></div>
              <h4 class="mb-0">{{ $astrologerAppointments->where("package", 3)->sum("total_price") }} </h4>

              <p class="small mb-0">Call Revenue</p>
            </div>
            </a>
          </div>
          <div class="col-md-2 col-6">
            <a href="{{ route('admin.consultation.video.index', ['Astrologer']) }}">
            <div class="card p-3">
              <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-currency-rupee" viewBox="0 0 16 16">
  <path d="M4 3.06h2.726c1.22 0 2.12.575 2.325 1.724H4v1.051h5.051C8.855 7.001 8 7.558 6.788 7.558H4v1.317L8.437 14h2.11L6.095 8.884h.855c2.316-.018 3.465-1.476 3.688-3.049H12V4.784h-1.345c-.08-.778-.357-1.335-.793-1.732H12V2H4z"/>
</svg></div>
              <h4 class="mb-0">{{ $astrologerAppointments->where("package", 1)->sum("total_price") }} </h4>

              <p class="small mb-0">Video Revenue</p>
            </div>
            </a>
          </div>
          <div class="col-md-2 col-6">
            <a href="{{ route('admin.aiconsultation.chat.index', ['AI']) }}">
            <div class="card p-3">
              <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-currency-rupee" viewBox="0 0 16 16">
  <path d="M4 3.06h2.726c1.22 0 2.12.575 2.325 1.724H4v1.051h5.051C8.855 7.001 8 7.558 6.788 7.558H4v1.317L8.437 14h2.11L6.095 8.884h.855c2.316-.018 3.465-1.476 3.688-3.049H12V4.784h-1.345c-.08-.778-.357-1.335-.793-1.732H12V2H4z"/>
</svg></div>
              <h4 class="mb-0">{{ $aiAstrologerAppointments->sum("charges") - $aiAstrologerAppointments->sum("discount") }} </h4>

              <p class="small mb-0">AI Chat Revenue</p>
            </div>
            </a>
          </div>
          <div class="col-md-2 col-6">
            <a href="{{ route('admin.dashboard.revenue.index', ['segment' => 'products']) }}">
            <div class="card p-3">
              <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-currency-rupee" viewBox="0 0 16 16">
  <path d="M4 3.06h2.726c1.22 0 2.12.575 2.325 1.724H4v1.051h5.051C8.855 7.001 8 7.558 6.788 7.558H4v1.317L8.437 14h2.11L6.095 8.884h.855c2.316-.018 3.465-1.476 3.688-3.049H12V4.784h-1.345c-.08-.778-.357-1.335-.793-1.732H12V2H4z"/>
</svg></div>
              <h4 class="mb-0">{{ $sold_products->sum("total_price") }} </h4>

              <p class="small mb-0">Products Revenue</p>
            </div>
            </a>
          </div>
          <div class="col-md-2 col-6">
            <a href="{{ route('admin.dashboard.revenue.index', ['segment' => 'puja']) }}">
            <div class="card p-3">
              <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-currency-rupee" viewBox="0 0 16 16">
  <path d="M4 3.06h2.726c1.22 0 2.12.575 2.325 1.724H4v1.051h5.051C8.855 7.001 8 7.558 6.788 7.558H4v1.317L8.437 14h2.11L6.095 8.884h.855c2.316-.018 3.465-1.476 3.688-3.049H12V4.784h-1.345c-.08-.778-.357-1.335-.793-1.732H12V2H4z"/>
</svg></div>
              <h4 class="mb-0">{{ $sold_poojas->sum("total_price") }} </h4>

              <p class="small mb-0">Puja Revenue</p>
            </div>
            </a>
          </div>
          <div class="col-md-2 col-6">
            <a href="{{ route('admin.dashboard.revenue.index', ['segment' => 'reports']) }}">
            <div class="card p-3">
              <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-currency-rupee" viewBox="0 0 16 16">
  <path d="M4 3.06h2.726c1.22 0 2.12.575 2.325 1.724H4v1.051h5.051C8.855 7.001 8 7.558 6.788 7.558H4v1.317L8.437 14h2.11L6.095 8.884h.855c2.316-.018 3.465-1.476 3.688-3.049H12V4.784h-1.345c-.08-.778-.357-1.335-.793-1.732H12V2H4z"/>
</svg></div>
              <h4 class="mb-0">{{ $sold_services->sum("total_price") }} </h4>

              <p class="small mb-0">Reports Revenue</p>
            </div>
            </a>
          </div>
          <div class="col-md-2 col-6">
            <a href="{{ route('admin.dashboard.free_kundali.index') }}">
            <div class="card p-3">
              <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-pdf" viewBox="0 0 16 16">
  <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1"/>
  <path d="M4.603 12.087a.8.8 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.7 7.7 0 0 1 1.482-.645 20 20 0 0 0 1.062-2.227 7.3 7.3 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.187-.012.395-.047.614-.084.51-.27 1.134-.52 1.794a11 11 0 0 0 .98 1.686 5.8 5.8 0 0 1 1.334.05c.364.065.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.86.86 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.7 5.7 0 0 1-.911-.95 11.6 11.6 0 0 0-1.997.406 11.3 11.3 0 0 1-1.021 1.51c-.29.35-.608.655-.926.787a.8.8 0 0 1-.58.029m1.379-1.901q-.25.115-.459.238c-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361q.016.032.026.044l.035-.012c.137-.056.355-.235.635-.572a8 8 0 0 0 .45-.606m1.64-1.33a13 13 0 0 1 1.01-.193 12 12 0 0 1-.51-.858 21 21 0 0 1-.5 1.05zm2.446.45q.226.244.435.41c.24.19.407.253.498.256a.1.1 0 0 0 .07-.015.3.3 0 0 0 .094-.125.44.44 0 0 0 .059-.2.1.1 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a4 4 0 0 0-.612-.053zM8.078 5.8a7 7 0 0 0 .2-.828q.046-.282.038-.465a.6.6 0 0 0-.032-.198.5.5 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822q.036.167.09.346z"/>
</svg></div>
              <h4 class="mb-0">{{ $kundaliEnquiry->count() }} </h4>

              <p class="small mb-0">Free Kundali</p>
            </div>
            </a>
          </div>
          <div class="col-md-2 col-6">
            <a href="{{ route('admin.dashboard.free_kundali_matching.index') }}">
            <div class="card p-3">
              <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-pdf" viewBox="0 0 16 16">
  <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1"/>
  <path d="M4.603 12.087a.8.8 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.7 7.7 0 0 1 1.482-.645 20 20 0 0 0 1.062-2.227 7.3 7.3 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.187-.012.395-.047.614-.084.51-.27 1.134-.52 1.794a11 11 0 0 0 .98 1.686 5.8 5.8 0 0 1 1.334.05c.364.065.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.86.86 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.7 5.7 0 0 1-.911-.95 11.6 11.6 0 0 0-1.997.406 11.3 11.3 0 0 1-1.021 1.51c-.29.35-.608.655-.926.787a.8.8 0 0 1-.58.029m1.379-1.901q-.25.115-.459.238c-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361q.016.032.026.044l.035-.012c.137-.056.355-.235.635-.572a8 8 0 0 0 .45-.606m1.64-1.33a13 13 0 0 1 1.01-.193 12 12 0 0 1-.51-.858 21 21 0 0 1-.5 1.05zm2.446.45q.226.244.435.41c.24.19.407.253.498.256a.1.1 0 0 0 .07-.015.3.3 0 0 0 .094-.125.44.44 0 0 0 .059-.2.1.1 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a4 4 0 0 0-.612-.053zM8.078 5.8a7 7 0 0 0 .2-.828q.046-.282.038-.465a.6.6 0 0 0-.032-.198.5.5 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822q.036.167.09.346z"/>
</svg></div>
              <h4 class="mb-0">{{ $kundaliMatchingEnquiry->count() }} </h4>

              <p class="small mb-0">Free Kundali Matching</p>
            </div>
            </a>
          </div>
          <div class="col-md-2 col-6">
            <a href="{{ route('admin.support') }}">
            <div class="card p-3">
              <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-headset" viewBox="0 0 16 16">
  <path d="M8 1a5 5 0 0 0-5 5v1h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V6a6 6 0 1 1 12 0v6a2.5 2.5 0 0 1-2.5 2.5H9.366a1 1 0 0 1-.866.5h-1a1 1 0 1 1 0-2h1a1 1 0 0 1 .866.5H11.5A1.5 1.5 0 0 0 13 12h-1a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h1V6a5 5 0 0 0-5-5"/>
</svg></div>
              <h4 class="mb-0">{{ $support->count() }} </h4>

              <p class="small mb-0">Open Support Tickets</p>
            </div>
            </a>
          </div>
          <div class="col-md-2 col-6">
            <a href="{{ route('admin.feedback.index') }}">
            <div class="card p-3">
              <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-chat-left-text" viewBox="0 0 16 16">
  <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
  <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6m0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5"/>
</svg></div>
              <h4 class="mb-0">{{ $customerFeedbackCount }} </h4>
              <p class="small mb-0">Customers Feedback</p>
            </div>
            </a>
          </div>

          <div class="col-md-2 col-6">
            <a href="{{ route('admin.customer.cartitem', [0]) }}">
            <div class="card p-3">
              <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-check" viewBox="0 0 16 16">
  <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/>
  <path d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z"/>
</svg></div>
              <h4 class="mb-0">{{ $users->total() }} </h4>
              <p class="small mb-0">Customers</p>
            </div>
          </a>
          </div>
        </div>
        <!-- education-box-end -->

        <div class="row mt-4 pb-4">
          <!-- Less than or equall to quantity products start -->
          <div class="col-md-6">
            <div class="bg-white rounded">
              <div class="d-flex align-items-center justify-content-between k_bg3 py-2">
                <h5 class="fw-bold m-0 p-0 heading px-3">Products Less Than Minumum Quantity</h5>
                <button class="btn btn-default m-1 py-2 k_view_all">
                  <a href="{{ route('admin.product.index', ['minimum_quantity' => 'show']) }}" target="_blank" class="k_color">View All <i class="bi bi-caret-down-fill k_down"></i></a>
                </button>
              </div>
              <div class="table-responsive">
                <table class="table w-100 dashe-main-table">
                  <thead class="table-light">
                    <tr>
                      <th>Image</th>
                      <th>Name</th>
                      <th>Quantiy</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($nofityProducts->count() > 0)
                      @foreach($nofityProducts->take(5) as $key => $value)
                        @php
                          $valueImage = $value->image;
                        @endphp
                        <tr>
                          <td class="d-flex">
                            <div class="pro-img-t">
                              <img src="{{$valueImage ? $valueImage->image(25, 25) : no_image()}}">
                            </div>
                          </td>
                          <td><a href="{{route('admin.product.edit', [$value->id])}}" target="_blank">{{ $value->title }}</a></td>
                          @php
                          $color = '';
                          if($value->avaibility == 0){
                            $color = 'red';
                          }
                          @endphp
                          <td style="color: {{$color}}">{{ $value->avaibility }}</td>
                        </tr>
                      @endforeach
                    @else
                      <tr>
                        <td colspan="3" class="text-center">No Data Found</td>
                      </tr>
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- Less than or equall to quantity products end -->

          <!-- recent-order-section start -->
          <div class="col-md-6">
            <div class="bg-white rounded">
              <div class="d-flex align-items-center justify-content-between k_bg1 py-2">
                <h5 class="fw-bold m-0 p-0 heading px-3">Recent Orders</h5>
                <button class="btn btn-default m-1 py-2 k_view_all">
                  <a href="{{ route('admin.order') }}" target="_blank" class="k_color">View All <i class="bi bi-caret-down-fill k_down"></i></a>
                </button>
              </div>
              <div class="table-responsive">
                <table class="table w-100 dashe-main-table">
                  <thead class="table-light">
                    <tr>
                      <th>Number</th>
                      <th>Date</th>
                      <th>Amount</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($orders->count() > 0)
                      @foreach($orders->take(5) as $key => $value)
                        <tr>
                          <td><a href="{{route('admin.order.edit', [$value->id])}}" target="_blank">{{$value->order_number}}</a></td>
                          <td>{{date('d-m-Y h:i a', strtotime($value->created_at))}}</td>
                          <td>₹{{sprintf('%.2f', $value->total_price)}}</td>
                          @php
                          $color = '';
                          if($value->order_status == 'delivered'){
                            $color = 'green';
                          }elseif($value->order_status == 'pending'){
                            $color = 'red';
                          }else{
                            $color = '#2984b1';
                          }
                          @endphp
                          <td style="color : {{$color}}">{{$value->order_status}}</td>
                        </tr>
                      @endforeach
                    @else
                      <tr>
                        <td colspan="4" class="text-center">No Data Found</td>
                      </tr>
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- recent-order-section-end -->
        </div>

        <!-- revenue-section-start -->
        <div class="row">
          <div class="col-md-6">
            <div class="bg-white rounded">
              <div class="d-flex align-items-center justify-content-between k_bg2 py-3">
                <h5 class="fw-bold m-0 p-0 heading px-3">Top Seller Products</h5>
                <!-- <button class="btn btn-default m-1 py-2">
                  <a href="#" target="_blank" class="text-black">View All</a>
                </button> -->
              </div>
              <!-- table-section-start -->
              <div class="table-responsive">
                <table class="table w-100  dashe-main-table">
                  <thead class="table-light">
                    <tr>
                      <th>Image</th>
                      <th>Name</th>
                      <th>Price</th>
                      <th>Sold</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($sold_products->count() > 0)
                      @foreach($sold_products->take(5) as $key => $value)
                        <tr>
                          <td class="d-flex">
                            <div class="pro-img-t">
                              <img src="{{$value->image?:no_image()}}" alt="{{$value->name}}">
                            </div>
                          </td>
                          @if($product = $value->product)
                          <td><a href="{{route('admin.product.edit', [$product->id])}}" target="_blank">{{$value->name}}</a></td>
                          @else
                          <td>{{$value->name}}</td>
                          @endif
                          <td>₹{{sprintf('%.2f', $value->price)}}</td>
                          <td class="k_all_color">{{$value->total_sold}}</td>
                        </tr>
                      @endforeach
                    @else
                      <tr>
                        <td colspan="4" class="text-center">No Data Found</td>
                      </tr>
                    @endif
                  </tbody>
                </table>
              </div>
              <!-- table-section-end -->
            </div>
          </div>
          <div class="col-md-6">
            <div class="bg-white rounded">
              <div class="d-flex align-items-center justify-content-between k_bg4 py-3">
                <h5 class="fw-bold m-0 p-0 heading px-3">Top Seller Puja's</h5>
                <!-- <button class="btn btn-default m-1 py-2">
                  <a href="#" target="_blank" class="text-black">View All</a>
                </button> -->
              </div>
              <!-- table-section-start -->
              <div class="table-responsive">
                <table class="table w-100  dashe-main-table">
                  <thead class="table-light">
                    <tr>
                      <th>Image</th>
                      <th>Name</th>
                      <th>Price</th>
                      <th>Sold</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($sold_poojas->count() > 0)
                      @foreach($sold_poojas->take(5) as $key => $value)
                        <tr>
                          <td class="d-flex">
                            <div class="pro-img-t">
                              <img src="{{$value->image?:no_image()}}" alt="{{$value->name}}">
                            </div>
                          </td>
                          @if($product = $value->product)
                          <td><a href="{{route('admin.product.edit', [$product->id])}}" target="_blank">{{$value->name}}</a></td>
                          @else
                          <td>{{$value->name}}</td>
                          @endif
                          <td>₹{{sprintf('%.2f', $value->price)}}</td>
                          <td class="k_all_color">{{$value->total_sold}}</td>
                        </tr>
                      @endforeach
                    @else
                      <tr>
                        <td colspan="4" class="text-center">No Data Found</td>
                      </tr>
                    @endif
                  </tbody>
                </table>
              </div>
              <!-- table-section-end -->
            </div>
          </div>
          <div class="col-md-6">
            <div class="bg-white rounded">
              <div class="d-flex align-items-center justify-content-between k_bg9 py-3">
                <h5 class="fw-bold m-0 p-0 heading px-3">Related Puja</h5>
                <!-- <button class="btn btn-default m-1 py-2">
                  <a href="#" target="_blank" class="text-black">View All</a>
                </button> -->
              </div>
              <!-- table-section-start -->
              <div class="table-responsive">
                <table class="table w-100  dashe-main-table">
                  <thead class="table-light">
                    <tr>
                      <th>Image</th>
                      <th>Name</th>
                      <th>Price</th>
                      <th>Sold</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($sold_poojas->count() > 0)
                      @foreach($sold_poojas->take(5) as $key => $value)
                        <tr>
                          <td class="d-flex">
                            <div class="pro-img-t">
                              <img src="{{$value->image?:no_image()}}" alt="{{$value->name}}">
                            </div>
                          </td>
                          @if($product = $value->product)
                          <td><a href="{{route('admin.product.edit', [$product->id])}}" target="_blank">{{$value->name}}</a></td>
                          @else
                          <td>{{$value->name}}</td>
                          @endif
                          <td>₹{{sprintf('%.2f', $value->price)}}</td>
                          <td class="k_all_color">{{$value->total_sold}}</td>
                        </tr>
                      @endforeach
                    @else
                      <tr>
                        <td colspan="4" class="text-center">No Data Found</td>
                      </tr>
                    @endif
                  </tbody>
                </table>
              </div>
              <!-- table-section-end -->
            </div>
          </div>

          <div class="col-md-6">
            <div class="bg-white rounded">
              <div class="d-flex align-items-center justify-content-between k_bg4 py-3">
                <h5 class="fw-bold m-0 p-0 heading px-3">Referral Management</h5>
                <!-- <button class="btn btn-default m-1 py-2">
                  <a href="#" target="_blank" class="text-black">View All</a>
                </button> -->
              </div>
              <!-- table-section-start -->
              <div class="table-responsive">
                <table class="table w-100  dashe-main-table">
                  <thead class="table-light">
                    <tr>
                      <th>Image</th>
                      <th>Name</th>
                      <th>Price</th>
                      <th>Sold</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($sold_poojas->count() > 0)
                      @foreach($sold_poojas->take(5) as $key => $value)
                        <tr>
                          <td class="d-flex">
                            <div class="pro-img-t">
                              <img src="{{$value->image?:no_image()}}" alt="{{$value->name}}">
                            </div>
                          </td>
                          @if($product = $value->product)
                          <td><a href="{{route('admin.product.edit', [$product->id])}}" target="_blank">{{$value->name}}</a></td>
                          @else
                          <td>{{$value->name}}</td>
                          @endif
                          <td>₹{{sprintf('%.2f', $value->price)}}</td>
                          <td class="k_all_color">{{$value->total_sold}}</td>
                        </tr>
                      @endforeach
                    @else
                      <tr>
                        <td colspan="4" class="text-center">No Data Found</td>
                      </tr>
                    @endif
                  </tbody>
                </table>
              </div>
              <!-- table-section-end -->
            </div>
          </div>

          <div class="col-md-6 mt-3">
            <div class="bg-white rounded">
              <div class="d-flex align-items-center justify-content-between k_bg5 py-3">
                <h5 class="fw-bold m-0 p-0 heading px-3">Top Seller Reports</h5>
                <!-- <button class="btn btn-default m-1 py-2">
                  <a href="#" target="_blank" class="text-black">View All</a>
                </button> -->
              </div>
              <!-- table-section-start -->
              <div class="table-responsive">
                <table class="table w-100  dashe-main-table">
                  <thead class="table-light">
                    <tr>
                      <th>Image</th>
                      <th>Name</th>
                      <th>Price</th>
                      <th>Sold</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($sold_services->count() > 0)
                      @foreach($sold_services->take(5) as $key => $value)
                        <tr>
                          <td class="d-flex">
                            <div class="pro-img-t">
                              <img src="{{$value->image?:no_image()}}" alt="{{$value->name}}">
                            </div>
                          </td>
                          @if($product = $value->product)
                          <td><a href="{{route('admin.product.edit', [$product->id])}}" target="_blank">{{$value->name}}</a></td>
                          @else
                          <td>{{$value->name}}</td>
                          @endif
                          <td>₹{{sprintf('%.2f', $value->price)}}</td>
                          <td class="k_all_color">{{$value->total_sold}}</td>
                        </tr>
                      @endforeach
                    @else
                      <tr>
                        <td colspan="4" class="text-center">No Data Found</td>
                      </tr>
                    @endif
                  </tbody>
                </table>
              </div>
              <!-- table-section-end -->
            </div>
          </div>

          <div class="col-md-6 mt-3">
            <div class="bg-white rounded">
              <div class="d-flex align-items-center justify-content-between k_bg6 py-3">
                <h5 class="fw-bold m-0 p-0 heading px-3">Top Humen Astrologers</h5>
                <!-- <button class="btn btn-default m-1 py-2">
                  <a href="#" target="_blank" class="text-black">View All</a>
                </button> -->
              </div>
              <!-- table-section-start -->
              <div class="table-responsive">
                <table class="table w-100  dashe-main-table">
                  <thead class="table-light">
                    <tr>
                      <th>Image</th>
                      <th>Name</th>
                      <th>Appointments</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($astrologerAppointments->count() > 0)
                      @foreach($astrologerAppointments->take(5) as $key => $value)
                        @php
                          $astrologer = $value->astrologer;
                          $astrologerImage = $astrologer ? $astrologer->image : null;
                        @endphp
                        <tr>
                          <td class="d-flex">
                            <div class="pro-img-t">
                              <img src="{{$astrologerImage ? $astrologerImage->image(25, 25) : no_image()}}">
                            </div>
                          </td>
                          <td><a href="{{route('admin.Astrologer.edit', [$value->id])}}" target="_blank">{{ $astrologer ? $astrologer->display_name : 'N/A' }}</a></td>
                          <td class="k_red_color">{{$value->total_appointments}}</td>
                        </tr>
                      @endforeach
                    @else
                      <tr>
                        <td colspan="3" class="text-center">No Data Found</td>
                      </tr>
                    @endif
                  </tbody>
                </table>
              </div>
              <!-- table-section-end -->
            </div>
          </div>

          <div class="col-md-4 col-12 mt-3">
            <div class="bg-white rounded">
              <div class="d-flex align-items-center justify-content-between k_bg7 py-3">
                <h5 class="fw-bold m-0 p-0 heading px-3">Top Ai Astrologers</h5>
                <!-- <button class="btn btn-default m-1 py-2">
                  <a href="#" target="_blank" class="text-black">View All</a>
                </button> -->
              </div>
              <!-- table-section-start -->
              <div class="table-responsive">
                <table class="table w-100  dashe-main-table">
                  <thead class="table-light">
                    <tr>
                      <th>Image</th>
                      <th>Name</th>
                      <th>Appointments</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($aiAstrologerAppointments->count() > 0)
                      @foreach($aiAstrologerAppointments->take(5) as $key => $value)
                        @php
                          $astrologer = $value->astrologer;
                          $astrologerImage = $astrologer ? $astrologer->image : null;
                        @endphp
                        <tr>
                          <td class="d-flex">
                            <div class="pro-img-t">
                              <img src="{{$astrologerImage ? $astrologerImage->image(25, 25) : no_image()}}">
                            </div>
                          </td>
                          <td><a href="{{route('admin.Astrologer.edit', [$value->id])}}" target="_blank">{{ $astrologer ? $astrologer->display_name : 'N/A' }}</a></td>
                          <td class="k_red_color">{{$value->total_appointments}}</td>
                        </tr>
                      @endforeach
                    @else
                      <tr>
                        <td colspan="3" class="text-center">No Data Found</td>
                      </tr>
                    @endif
                  </tbody>
                </table>
              </div>
              <!-- table-section-end -->
            </div>
          </div>

          {{--<div class="col-md-6 mt-3">
            <div class="bg-white  rounded">
              <div class="d-flex align-items-center justify-content-between bg-p py-2">
                <h5 class="fw-bold m-0 p-0 heading px-3">Active Customers</h5>
                <button class="btn btn-default m-1 py-2 k_view_all">
                  <a href="{{route('admin.customer.index', ['status' => 'active'])}}" target="_blank" class="k_color">View All <i class="bi bi-caret-down-fill k_down"></i></a>
                </button>
              </div>
              <!-- table-section-start -->
              <div class="table-responsive">
                <table class="table w-100  dashe-main-table">
                  <thead class="table-light">
                    <tr>
                      <th>Image</th>
                      <th>Name</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($activeUsers->count() > 0)
                      @foreach($activeUsers->take(5) as $key => $value)
                        <tr>
                          <td class="d-flex">
                            <div class="pro-img-t">
                              <img src="{{$value->image?$value->image->image('100', 100): no_image()}}">
                            </div>
                          </td>
                          <td><a href="{{route('admin.customer.edit', [$value->id])}}" target="_blank">{{ $value->name }}</a></td>
                        </tr>
                      @endforeach
                    @else
                      <tr>
                        <td colspan="2" class="text-center">No Data Found</td>
                      </tr>
                    @endif
                  </tbody>
                </table>
              </div>
              <!-- table-section-end -->
            </div>
          </div>--}}

          {{--<div class="col-md-6 mt-3">
            <div class="bg-white  rounded">
              <div class="d-flex align-items-center justify-content-between bg-p py-2">
                <h5 class="fw-bold m-0 p-0 heading px-3">Active Humen Astrologers</h5>
                <button class="btn btn-default m-1 py-2 k_view_all">
                  <a href="{{route('admin.Astrologer.index', ['status' => 'active'])}}" target="_blank" class="k_color">View All <i class="bi bi-caret-down-fill k_down"></i></a>
                </button>
              </div>
              <!-- table-section-start -->
              <div class="table-responsive">
                <table class="table w-100  dashe-main-table">
                  <thead class="table-light">
                    <tr>
                      <th>Image</th>
                      <th>Name</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($activeHumenAstrologers->count() > 0)
                      @foreach($activeHumenAstrologers->take(5) as $key => $value)
                        <tr>
                          <td class="d-flex">
                            <div class="pro-img-t">
                              <img src="{{$value->image?$value->image->image('100', 100): no_image()}}">
                            </div>
                          </td>
                          <td><a href="{{route('admin.Astrologer.edit', [$value->id])}}" target="_blank">{{ $value->display_name }}</a></td>
                        </tr>
                      @endforeach
                    @else
                      <tr>
                        <td colspan="2" class="text-center">No Data Found</td>
                      </tr>
                    @endif
                  </tbody>
                </table>
              </div>
              <!-- table-section-end -->
            </div>
          </div>--}}

          {{--<div class="col-md-6 mt-3">
            <div class="bg-white  rounded">
              <div class="d-flex align-items-center justify-content-between bg-p py-2">
                <h5 class="fw-bold m-0 p-0 heading px-3">Active AI Astrologers</h5>
                <button class="btn btn-default m-1 py-2 k_view_all">
                  <a href="{{route('admin.AI.index', ['status' => 'active'])}}" target="_blank" class="k_color">View All <i class="bi bi-caret-down-fill k_down"></i></a>
                </button>
              </div>
              <!-- table-section-start -->
              <div class="table-responsive">
                <table class="table w-100  dashe-main-table">
                  <thead class="table-light">
                    <tr>
                      <th>Image</th>
                      <th>Name</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($activeAiAstrologers->count() > 0)
                      @foreach($activeAiAstrologers->take(5) as $key => $value)
                        <tr>
                          <td class="d-flex">
                            <div class="pro-img-t">
                              <img src="{{$value->image?$value->image->image('100', 100): no_image()}}">
                            </div>
                          </td>
                          <td><a href="{{route('admin.AI.edit', [$value->id])}}" target="_blank">{{ $value->display_name }}</a></td>
                        </tr>
                      @endforeach
                    @else
                      <tr>
                        <td colspan="2" class="text-center">No Data Found</td>
                      </tr>
                    @endif
                  </tbody>
                </table>
              </div>
              <!-- table-section-end -->
            </div>
          </div>--}}



          <div class="col-md-4 mt-3">
            <div class="bg-white rounded">
              <div class="d-flex align-items-center justify-content-between k_bg9 py-2">
                <h5 class="fw-bold m-0 p-0 heading px-3">Recent Humen Astrologers</h5>
                <button class="btn btn-default m-1 py-2 k_view_all">
                  <a href="{{route('admin.Astrologer.index')}}" target="_blank" class="k_color">View All <i class="bi bi-caret-down-fill k_down"></i></a>
                </button>
              </div>
              <!-- table-section-start -->
              <div class="table-responsive">
                <table class="table w-100  dashe-main-table">
                  <thead class="table-light">
                    <tr>
                      <th>Image</th>
                      <th>Name</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($astrologers->count() > 0)
                      @foreach($astrologers->take(5) as $key => $value)
                        <tr>
                          <td class="d-flex">
                            <div class="pro-img-t">
                              <img src="{{$value->image?$value->image->image('100', 100): no_image()}}">
                            </div>
                          </td>
                          <td><a href="{{route('admin.Astrologer.edit', [$value->id])}}" target="_blank">{{ $value->display_name }}</a></td>
                        </tr>
                      @endforeach
                    @else
                      <tr>
                        <td colspan="2" class="text-center">No Data Found</td>
                      </tr>
                    @endif
                  </tbody>
                </table>
              </div>
              <!-- table-section-end -->
            </div>
          </div>

          <div class="col-md-4 mt-3">
            <div class="bg-white rounded">
              <div class="d-flex align-items-center justify-content-between k_bg10 py-2">
                <h5 class="fw-bold m-0 p-0 heading px-3">Recent AI Astrologers</h5>
                <button class="btn btn-default m-1 py-2 k_view_all">
                  <a href="{{route('admin.AI.index')}}" target="_blank" class="k_color">View All <i class="bi bi-caret-down-fill k_down"></i></a>
                </button>
              </div>
              <!-- table-section-start -->
              <div class="table-responsive">
                <table class="table w-100  dashe-main-table">
                  <thead class="table-light">
                    <tr>
                      <th>Image</th>
                      <th>Name</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($aiAstrologers->count() > 0)
                      @foreach($aiAstrologers->take(5) as $key => $value)
                        <tr>
                          <td class="d-flex">
                            <div class="pro-img-t">
                              <img src="{{$value->image?$value->image->image('100', 100): no_image()}}">
                            </div>
                          </td>
                          <td><a href="{{route('admin.AI.edit', [$value->id])}}" target="_blank">{{ $value->display_name }}</a></td>
                        </tr>
                      @endforeach
                    @else
                      <tr>
                        <td colspan="2" class="text-center">No Data Found</td>
                      </tr>
                    @endif
                  </tbody>
                </table>
              </div>
              <!-- table-section-end -->
            </div>
          </div>
          <div class="col-md-4 mt-3">
            <div class="bg-white rounded">
              <div class="d-flex align-items-center justify-content-between k_bg4 py-2">
                <h5 class="fw-bold m-0 p-0 heading px-3">Staff Users</h5>
              </div>
              <!-- table-section-start -->
              <div class="table-responsive">
                <table class="table w-100  dashe-main-table">
                  <thead class="table-light">
                    <tr>
                      <th>Image</th>
                      <th>Name</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($allAdminsWithStatus->count() > 0)
                      @foreach($allAdminsWithStatus as $admin)
                        @php
                          $isActive = $admin->status == 1;
                          $isOnline = $admin->is_online ?? false;
                        @endphp
                        <tr>
                          <td class="d-flex">
                            <div class="pro-img-t position-relative">
                              <img src="{{ asset('theme-admin/imgs/avatar-1.png') }}"
                                   alt="{{ $admin->name }}"
                                   style="width: 30px; height: 30px; object-fit: cover; border: 2px solid #ddd; border-radius: 50%;">
                              <span class="position-absolute rounded-circle border border-white"
                                    style="width: 10px; height: 10px; background-color: {{ $isActive && $isOnline ? '#28a745' : '#6c757d' }}; display: block; top: 0px; right: 0px; z-index: 10;"></span>
                            </div>
                          </td>
                          <td>{{ $admin->name }}</td>
                        </tr>
                      @endforeach
                    @else
                      <tr>
                        <td colspan="2" class="text-center">No admin users found</td>
                      </tr>
                    @endif
                  </tbody>
                </table>
              </div>
              <!-- table-section-end -->
            </div>
          </div>
        </div>
        <!-- revenue-section-end -->

      </div>

<!-- new-dashboard-section-end -->
@endsection
@push('scripts')
@endpush
