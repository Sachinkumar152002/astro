@extends('layouts.admin')

@section('content')
<div class="row">
<div class="col-md-6">
  @if(!$tab)
    <h1 class="text-primary float-left">All Settings</h1>
  @endif
</div>
<div class="col-md-6">
  <a href="{{route('admin.clearcache')}}" class="btn btn-danger btn-sm float-right" > Clear Cache </a>
</div>
</div>
<div class="clearfix"></div>
<form action="{{route('admin.settings')}}" method="post" id="setting-form" onsubmit="return validateAndSubmitPageFunction(this)">
  {{ csrf_field() }}
  <div class="clearfix"></div>
  <input type="hidden" name="tab" value="{{$tab}}" >
  @php
    $falseClass = "";
    if($tab){
      $falseClass = "d-none";
    }
  @endphp
    <ul class="nav nav-tabs">
      <li class="nav-item {{ $falseClass == '' ? 'active' : '' }} {{ $tab == 'Default' ? 'active' : $falseClass }}">
        <a data-toggle="tab" class="{{ $falseClass == '' ? 'active' : '' }} {{ $tab == 'Default' ? 'active' : $falseClass }} nav-link" href="#information_tab">Default</a>
      </li>
      <li class="nav-item {{ $tab == 'Social' ? 'active' : $falseClass }}">
        <a data-toggle="tab" class="nav-link {{ $tab == 'Social' ? 'active' : $falseClass }}" href="#social_links">Social Links</a>
      </li>
      <li class="nav-item {{ $tab == 'SEO' ? 'active' : $falseClass }}">
        <a data-toggle="tab" class="nav-link {{ $tab == 'SEO' ? 'active' : $falseClass }}" href="#seo">SEO</a>
      </li>
      <li class="nav-item {{ $tab == 'My Profile' ? 'active' : $falseClass }}">
        <a data-toggle="tab" class="nav-link {{ $tab == 'My Profile' ? 'active' : $falseClass }}" href="#user">My Profile</a>
      </li>
      @if(false)
        <li class="nav-item {{ $tab == 'Platform Charges' ? 'active' : $falseClass }}">
          <a data-toggle="tab" class="nav-link {{ $tab == 'Platform Charges' ? 'active' : $falseClass }}" href="#platform_charges">Platform Charges</a>
        </li>
        <li class="nav-item {{ $tab == 'Third Party/Tools Charges' ? 'active' : $falseClass }}">
          <a data-toggle="tab" class="nav-link {{ $tab == 'Third Party/Tools Charges' ? 'active' : $falseClass }}" href="#services_charges">Third Party/Tools Charges</a>
        </li>
      @endif
      <li class="nav-item {{ $tab == 'Withdraw' ? 'active' : $falseClass }}">
        <a data-toggle="tab" class="nav-link {{ $tab == 'Withdraw' ? 'active' : $falseClass }}" href="#withdraw">Withdraw</a>
      </li>
      @if(false)
        <li class="nav-item">
          <a data-toggle="tab" class="nav-link" href="#file">File</a>
        </li>
        <li class="nav-item {{ $tab == 'Other' ? 'active' : $falseClass }}">
          <a data-toggle="tab" class="nav-link {{ $tab == 'Other' ? 'active' : $falseClass }}" href="#other">Other</a>
        </li>
      @endif
      <li class="nav-item {{ $tab == 'Api' ? 'active' : $falseClass }}">
        <a data-toggle="tab" class="nav-link {{ $tab == 'Api' ? 'active' : $falseClass }}" href="#astro_api">Api</a>
      </li>
      <li class="nav-item {{ $tab == 'Other' ? 'active' : $falseClass }}">
        <a data-toggle="tab" class="nav-link {{ $tab == 'Other' ? 'active' : $falseClass }}" href="#other">Other</a>
      </li>
      <li class="nav-item {{ $tab == 'Site Login' ? 'active' : $falseClass }}">
        <a data-toggle="tab" class="nav-link {{ $tab == 'Site Login' ? 'active' : $falseClass }}" href="#site_login">Site Login</a>
      </li>
    </ul>
    <div class="tab-content">
      @php
      $defaultFields = ['logo', 'contact', 'whatsapp', 'contact_email', 'address', 'director_name', 'director_sign'];
      $socialFields = ['fb_link', 'tw_link', 'tw_link', 'linkedin_link', 'instagram_link', 'youtube_link'];
      $seoFields = ['website_title', 'description', 'keywords', 'header_script', 'footer_script', "follow_tag", "index_tag"];
      $userFields = ['username', 'user_group', 'first_name', 'last_name', 'email', 'user_image', 'password', 'confirm', 'status'];
      $platformFields = ['server', 'sms', 'email_charges', 'maintainance_charge'];
      $servicesFields = ['call_charges', 'video_charges', 'chat_charges', 'payment_gateway'];
      $withdrawFields = ['minumum_withraw_amount', 'maximum_withraw_amount', 'hold_withraw_amount'];
      $other = ['cancel_before', 'recharge_hsn','shipping_charges', 'welcome_bonus', "cod",'youtube_channel_link_astrologer_training'];
      $files = ['course_brochure'];
      $astroapi = ["razorpay_api_key", "razorpay_api_secret_key", "razorpay_api_about", 'astro_api_key', 'astro_api_about', "agora_app_id", "agora_app_certificate", "agora_app_about", "openai_api_key", "openai_api_about", "divine_api_key", "divine_api_token", "divine_api_about", "meta_pixel_id", "meta_capi_access_token", "meta_about_conversion_api",'exotel_api_key','exotel_api_token','exotel_api_sid'];
      $siteLogin = ['website_url', 'website_username', 'website_password'];
      @endphp
      <div id="information_tab" class="tab-pane fade in {{ $falseClass == '' ? 'active show' : '' }} {{ $tab == 'Default' ? 'active show' : $falseClass }}">
          <table class="table table-hover">
              <!-- <thead>
                  <tr>
                      <th>Name</th>
                      <th>Value</th>
                  </tr>
              </thead> -->
              <tbody>
                  @forelse ($settings->whereIn('key',  $defaultFields) as $item)
                    @if(in_array($item->key, $defaultFields))
                        @if($item->type == 'image')
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <div class="row" id="image-gallery">
                                <div class="form-group col-4">
                                    <div class="">
                                       <button type="button" class="btn btn-sm mx-2"  data-toggle="modal" onclick="openMediaManager('{{$item->key}}')">Upload Image</button>
                                       <input type="hidden" id="{{$item->key}}_id" name="{{$item->key}}" value="{{$item->value}}">
                                       <input type="hidden" id="{{$item->key}}_src" name="{{$item->key}}_src" value="{{$item->{$item->key.'Fn'} ? $item->{$item->key.'Fn'}->image(200,200) : '' }}">
                                      <div class="{{$item->key}}_image float-right">
                                        <img src="{{$item->{$item->key.'Fn'} ? $item->{$item->key.'Fn'}->image(200,200) : ''}}" short_name="icon" class="img-responsive" style="width:110px;height:50px">
                                        <i class="fa fa-trash text-danger" onclick="removeImage(this, '{{$item->key}}')"></i>
                                      </div>
                                    </div>
                                </div>
                              </div>
                            </td>
                          </tr>
                        @elseif($item->type == 'textarea')
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <textarea name="{{$item->key}}" class="form-control" rows="8" cols="80">{{$item->value}}</textarea>
                            </td>
                          </tr>
                          @else
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <input type="text" name="{{$item->key}}" value="{{$item->value}}" class="form-control">
                            </td>
                          </tr>
                        @endif
                      @endif
                  @empty
                      <tr>
                          <td colspan="2">No setting found</td>
                      </tr>
                  @endforelse
              </tbody>
          </table>
      </div>
      <div id="social_links" class="tab-pane fade in {{ $tab == 'Social' ? 'active show' : $falseClass }}">
          <table class="table table-hover">
              <!-- <thead>
                  <tr>
                      <th>Name</th>
                      <th>Value</th>
                  </tr>
              </thead> -->
              <tbody>
                  @forelse ($settings->whereIn('key',  $socialFields) as $item)
                    @if(in_array($item->key, $socialFields))
                        @if($item->type == 'image')
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <div class="row" id="image-gallery">
                                <div class="form-group col-4">
                                    <div class="">
                                       <button type="button" class="btn btn-sm mx-2"  data-toggle="modal" onclick="openMediaManager('{{$item->key}}')">Upload Image</button>
                                       <input type="hidden" id="{{$item->key}}_id" name="{{$item->key}}" value="{{$item->value}}">
                                       <input type="hidden" id="{{$item->key}}_src" name="{{$item->key}}_src" value="{{$item->{$item->key.'Fn'} ? $item->{$item->key.'Fn'}->image(200,200) : '' }}">
                                      <div class="{{$item->key}}_image float-right">
                                        <img src="{{$item->{$item->key.'Fn'} ? $item->{$item->key.'Fn'}->image(200,200) : ''}}" short_name="icon" class="img-responsive" style="width:100px;height:100px">
                                        <i class="fa fa-trash text-danger" onclick="removeImage(this, '{{$item->key}}')"></i>
                                      </div>
                                    </div>
                                </div>
                              </div>
                            </td>
                          </tr>
                        @else
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <input type="text" name="{{$item->key}}" value="{{$item->value}}" class="form-control">
                            </td>
                          </tr>
                        @endif
                      @endif
                  @empty
                      <tr>
                          <td colspan="2">No setting found</td>
                      </tr>
                  @endforelse
              </tbody>
          </table>
      </div>
      <div id="seo" class="tab-pane fade in {{ $tab == 'SEO' ? 'active show' : $falseClass }}">
          <table class="table table-hover">
              <!-- <thead>
                  <tr>
                      <th>Name</th>
                      <th>Value</th>
                  </tr>
              </thead> -->
              <tbody>
                  @forelse ($settings->whereIn('key',  $seoFields) as $item)
                    @if(in_array($item->key, $seoFields))
                        @if($item->type == 'image')
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <div class="row" id="image-gallery">
                                <div class="form-group col-4">
                                    <div class="">
                                       <button type="button" class="btn btn-sm mx-2"  data-toggle="modal" onclick="openMediaManager('{{$item->key}}')">Upload Image</button>
                                       <input type="hidden" id="{{$item->key}}_id" name="{{$item->key}}" value="{{$item->value}}">
                                       <input type="hidden" id="{{$item->key}}_src" name="{{$item->key}}_src" value="{{$item->{$item->key.'Fn'} ? $item->{$item->key.'Fn'}->image(200,200) : '' }}">
                                      <div class="{{$item->key}}_image float-right">
                                        <img src="{{$item->{$item->key.'Fn'} ? $item->{$item->key.'Fn'}->image(200,200) : ''}}" short_name="icon" class="img-responsive" style="width:100px;height:100px">
                                        <i class="fa fa-trash text-danger" onclick="removeImage(this, '{{$item->key}}')"></i>
                                      </div>
                                    </div>
                                </div>
                              </div>
                            </td>
                          </tr>
                        @elseif($item->type == 'textarea')
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <textarea name="{{$item->key}}" class="form-control" rows="8" cols="80">{{$item->value}}</textarea>
                            </td>
                          </tr>
                        @elseif($item->type == "toggle")
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <div class="form-group col-md-3 px-1">
                                  <input type="checkbox" data-id="{{ $item->key }}" class="toggle-check" value="1" id="{{ $item->key }}_checkbox" {{$item->value==1 ? 'checked' : ''}}>
                                  <label class="mx-2 t-lable mt-3" for="{{ $item->key }}_checkbox">{{ $item->name }}</label>
                              </div>
                              <input type="hidden" value="0" id="{{ $item->key }}" name="{{ $item->key }}">
                            </td>
                          </tr>
                        @else
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <input type="text" name="{{$item->key}}" value="{{$item->value}}" class="form-control">
                            </td>
                          </tr>
                        @endif
                      @endif
                  @empty
                      <tr>
                          <td colspan="2">No setting found</td>
                      </tr>
                  @endforelse
              </tbody>
          </table>
      </div>
      <div id="user" class="tab-pane fade in {{ $tab == 'My Profile' ? 'active show' : $falseClass }}">
          <table class="table table-hover">
              <tbody>
                  @forelse ($settings->whereIn('key',  $userFields) as $item)
                    @if(in_array($item->key, $userFields))
                        @if($item->type == 'image')
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <div class="row" id="image-gallery">
                                <div class="form-group col-4">
                                    <div class="">
                                       <button type="button" class="btn btn-sm mx-2"  data-toggle="modal" onclick="openMediaManager('{{$item->key}}')">Upload Image</button>
                                       <input type="hidden" id="{{$item->key}}_id" name="{{$item->key}}" value="{{$item->value}}">
                                       <input type="hidden" id="{{$item->key}}_src" name="{{$item->key}}_src" value="{{$item->{$item->key.'Fn'} ? $item->{$item->key.'Fn'}->image(200,200) : '' }}">
                                      <div class="{{$item->key}}_image float-right">
                                        <img src="{{$item->{$item->key.'Fn'} ? $item->{$item->key.'Fn'}->image(200,200) : ''}}" short_name="icon" class="img-responsive" style="width:100px;height:100px">
                                        <i class="fa fa-trash text-danger" onclick="removeImage(this, '{{$item->key}}')"></i>
                                      </div>
                                    </div>
                                </div>
                              </div>
                            </td>
                          </tr>
                        @elseif($item->type == 'textarea')
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <textarea name="{{$item->key}}" class="form-control" rows="8" cols="80">{{$item->value}}</textarea>
                            </td>
                          </tr>
                        @else
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <input type="text" name="{{$item->key}}" {{ $item->key == "email" ? "readonly" : "" }} value="{{$item->value}}" class="form-control">
                            </td>
                          </tr>
                        @endif
                      @endif
                  @empty
                      <tr>
                          <td colspan="2">No setting found</td>
                      </tr>
                  @endforelse
              </tbody>
          </table>
      </div>
      <div id="platform_charges" class="tab-pane fade in {{ $tab == 'Platform Charges' ? 'active show' : $falseClass }}">
          <table class="table table-hover">
              <!-- <thead>
                  <tr>
                      <th>Name</th>
                      <th>Value</th>
                  </tr>
              </thead> -->
              <tbody>
                  @forelse ($settings->whereIn('key',  $platformFields) as $item)
                    @if(in_array($item->key, $platformFields))
                        @if($item->type == 'image')
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <div class="row" id="image-gallery">
                                <div class="form-group col-4">
                                    <div class="">
                                       <button type="button" class="btn btn-sm mx-2"  data-toggle="modal" onclick="openMediaManager('{{$item->key}}')">Upload Image</button>
                                       <input type="hidden" id="{{$item->key}}_id" name="{{$item->key}}" value="{{$item->value}}">
                                       <input type="hidden" id="{{$item->key}}_src" name="{{$item->key}}_src" value="{{$item->{$item->key.'Fn'} ? $item->{$item->key.'Fn'}->image(200,200) : '' }}">
                                      <div class="{{$item->key}}_image float-right">
                                        <img src="{{$item->{$item->key.'Fn'} ? $item->{$item->key.'Fn'}->image(200,200) : ''}}" short_name="icon" class="img-responsive" style="width:100px;height:100px">
                                        <i class="fa fa-trash text-danger" onclick="removeImage(this, '{{$item->key}}')"></i>
                                      </div>
                                    </div>
                                </div>
                              </div>
                            </td>
                          </tr>
                        @else
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <input type="text" name="{{$item->key}}" value="{{$item->value}}" class="form-control">
                            </td>
                          </tr>
                        @endif
                      @endif
                  @empty
                      <tr>
                          <td colspan="2">No setting found</td>
                      </tr>
                  @endforelse
              </tbody>
          </table>
      </div>
      <div id="services_charges" class="tab-pane fade in {{ $tab == 'Third Party/Tools Charges' ? 'active show' : $falseClass }}">
          <table class="table table-hover">
              <!-- <thead>
                  <tr>
                      <th>Name</th>
                      <th>Value</th>
                  </tr>
              </thead> -->
              <tbody>
                  @forelse ($settings->whereIn('key',  $servicesFields) as $item)
                    @if(in_array($item->key, $servicesFields))
                        @if($item->type == 'image')
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <div class="row" id="image-gallery">
                                <div class="form-group col-4">
                                    <div class="">
                                       <button type="button" class="btn btn-sm mx-2"  data-toggle="modal" onclick="openMediaManager('{{$item->key}}')">Upload Image</button>
                                       <input type="hidden" id="{{$item->key}}_id" name="{{$item->key}}" value="{{$item->value}}">
                                       <input type="hidden" id="{{$item->key}}_src" name="{{$item->key}}_src" value="{{$item->{$item->key.'Fn'} ? $item->{$item->key.'Fn'}->image(200,200) : '' }}">
                                      <div class="{{$item->key}}_image float-right">
                                        <img src="{{$item->{$item->key.'Fn'} ? $item->{$item->key.'Fn'}->image(200,200) : ''}}" short_name="icon" class="img-responsive" style="width:100px;height:100px">
                                        <i class="fa fa-trash text-danger" onclick="removeImage(this, '{{$item->key}}')"></i>
                                      </div>
                                    </div>
                                </div>
                              </div>
                            </td>
                          </tr>
                        @else
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <input type="text" name="{{$item->key}}" value="{{$item->value}}" class="form-control">
                            </td>
                          </tr>
                        @endif
                      @endif
                  @empty
                      <tr>
                          <td colspan="2">No setting found</td>
                      </tr>
                  @endforelse
              </tbody>
          </table>
      </div>
      <div id="withdraw" class="tab-pane fade in {{ $tab == 'Withdraw' ? 'active show' : $falseClass }}">
          <table class="table table-hover">
              <!-- <thead>
                  <tr>
                      <th>Name</th>
                      <th>Value</th>
                  </tr>
              </thead> -->
              <tbody>
                  @forelse ($settings->whereIn('key',  $withdrawFields) as $item)
                    @if(in_array($item->key, $withdrawFields))
                        @if($item->type == 'image')
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <div class="row" id="image-gallery">
                                <div class="form-group col-4">
                                    <div class="">
                                       <button type="button" class="btn btn-sm mx-2"  data-toggle="modal" onclick="openMediaManager('{{$item->key}}')">Upload Image</button>
                                       <input type="hidden" id="{{$item->key}}_id" name="{{$item->key}}" value="{{$item->value}}">
                                       <input type="hidden" id="{{$item->key}}_src" name="{{$item->key}}_src" value="{{$item->{$item->key.'Fn'} ? $item->{$item->key.'Fn'}->image(200,200) : '' }}">
                                      <div class="{{$item->key}}_image float-right">
                                        <img src="{{$item->{$item->key.'Fn'} ? $item->{$item->key.'Fn'}->image(200,200) : ''}}" short_name="icon" class="img-responsive" style="width:100px;height:100px">
                                        <i class="fa fa-trash text-danger" onclick="removeImage(this, '{{$item->key}}')"></i>
                                      </div>
                                    </div>
                                </div>
                              </div>
                            </td>
                          </tr>
                        @else
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <input type="text" name="{{$item->key}}" value="{{$item->value}}" class="form-control">
                            </td>
                          </tr>
                        @endif
                      @endif
                  @empty
                      <tr>
                          <td colspan="2">No setting found</td>
                      </tr>
                  @endforelse
              </tbody>
          </table>
      </div>
      <div id="file" class="tab-pane fade in">
          <table class="table table-hover">
              <tbody>
                  @forelse ($settings->whereIn('key',  $files) as $item)
                    @if(in_array($item->key, $files))
                        @if($item->type == 'image')
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <div class="row" id="image-gallery">
                                <div class="form-group col-4">
                                    <div class="">
                                       <button type="button" class="btn btn-sm mx-2"  data-toggle="modal" onclick="openMediaManager('{{$item->key}}')">Upload Image</button>
                                       <input type="hidden" id="{{$item->key}}_id" name="{{$item->key}}" value="{{$item->value}}">
                                       <input type="hidden" id="{{$item->key}}_src" name="{{$item->key}}_src" value="{{$item->{$item->key.'Fn'} ? $item->{$item->key.'Fn'}->image(200,200) : '' }}">
                                      <div class="{{$item->key}}_image float-right">
                                        <img src="{{$item->{$item->key.'Fn'} ? $item->{$item->key.'Fn'}->image(200,200) : ''}}" short_name="icon" class="img-responsive" style="width:100px;height:100px">
                                        <i class="fa fa-trash text-danger" onclick="removeImage(this, '{{$item->key}}')"></i>
                                      </div>
                                    </div>
                                </div>
                              </div>
                            </td>
                          </tr>
                        @elseif($item->type == 'file')
                        <tr>
                          <td>{{$item->name}}</td>
                          <td>
                            <input type="file" accept=".pdf" name="{{$item->key}}" class="form-control">
                            @if($item->value)
                              <input type="text" name="{{$item->key}}_value" value="{{$item->value}}" class="form-control">
                            @endif
                          </td>
                        </tr>
                        @else
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <input type="text" name="{{$item->key}}" value="{{$item->value}}" class="form-control">
                            </td>
                          </tr>
                        @endif
                      @endif
                  @empty
                      <tr>
                          <td colspan="2">No setting found</td>
                      </tr>
                  @endforelse
              </tbody>
          </table>
      </div>
      <div id="site_login" class="tab-pane fade in {{ $tab == 'Site Login' ? 'active show' : $falseClass }}">
          <table class="table table-hover">
              <!-- <thead>
                  <tr>
                      <th>Name</th>
                      <th>Value</th>
                  </tr>
              </thead> -->
              <tbody>
                  @forelse ($settings->whereIn('key',  $siteLogin) as $item)
                    @if(in_array($item->key, $siteLogin))
                        @if($item->type == 'image')
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <div class="row" id="image-gallery">
                                <div class="form-group col-4">
                                    <div class="">
                                       <button type="button" class="btn btn-sm mx-2"  data-toggle="modal" onclick="openMediaManager('{{$item->key}}')">Upload Image</button>
                                       <input type="hidden" id="{{$item->key}}_id" name="{{$item->key}}" value="{{$item->value}}">
                                       <input type="hidden" id="{{$item->key}}_src" name="{{$item->key}}_src" value="{{$item->{$item->key.'Fn'} ? $item->{$item->key.'Fn'}->image(200,200) : '' }}">
                                      <div class="{{$item->key}}_image float-right">
                                        <img src="{{$item->{$item->key.'Fn'} ? $item->{$item->key.'Fn'}->image(200,200) : ''}}" short_name="icon" class="img-responsive" style="width:100px;height:100px">
                                        <i class="fa fa-trash text-danger" onclick="removeImage(this, '{{$item->key}}')"></i>
                                      </div>
                                    </div>
                                </div>
                              </div>
                            </td>
                          </tr>
                        @else
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <input type="text" name="{{$item->key}}" value="{{$item->value}}" class="form-control">
                            </td>
                          </tr>
                        @endif
                      @endif
                  @empty
                      <tr>
                          <td colspan="2">No setting found</td>
                      </tr>
                  @endforelse
              </tbody>
          </table>
      </div>
      <div id="other" class="tab-pane fade in {{ $tab == 'Other' ? 'active show' : $falseClass }}">
          <table class="table table-hover">
              <!-- <thead>
                  <tr>
                      <th>Name</th>
                      <th>Value</th>
                  </tr>
              </thead> -->
              <tbody>
                  @forelse ($settings->whereIn('key',  $other) as $item)
                    @if(in_array($item->key, $other))
                        @if($item->type == 'image')
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <div class="row" id="image-gallery">
                                <div class="form-group col-4">
                                    <div class="">
                                       <button type="button" class="btn btn-sm mx-2"  data-toggle="modal" onclick="openMediaManager('{{$item->key}}')">Upload Image</button>
                                       <input type="hidden" id="{{$item->key}}_id" name="{{$item->key}}" value="{{$item->value}}">
                                       <input type="hidden" id="{{$item->key}}_src" name="{{$item->key}}_src" value="{{$item->{$item->key.'Fn'} ? $item->{$item->key.'Fn'}->image(200,200) : '' }}">
                                      <div class="{{$item->key}}_image float-right">
                                        <img src="{{$item->{$item->key.'Fn'} ? $item->{$item->key.'Fn'}->image(200,200) : ''}}" short_name="icon" class="img-responsive" style="width:100px;height:100px">
                                        <i class="fa fa-trash text-danger" onclick="removeImage(this, '{{$item->key}}')"></i>
                                      </div>
                                    </div>
                                </div>
                              </div>
                            </td>
                          </tr>
                        @elseif($item->type == "toggle")
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <div class="form-group col-md-3 px-1">
                                  <input type="checkbox" data-id="{{ $item->key }}" class="toggle-check" value="1" id="{{ $item->key }}_checkbox" {{$item->value==1 ? 'checked' : ''}}>
                                  <label class="mx-2 t-lable mt-3" for="{{ $item->key }}_checkbox">{{ $item->name }}</label>
                              </div>
                              <input type="hidden" value="0" id="{{ $item->key }}" name="{{ $item->key }}">
                            </td>
                          </tr>
                        @else
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <input type="text" name="{{$item->key}}" value="{{$item->value}}" class="form-control">
                            </td>
                          </tr>
                        @endif
                      @endif
                  @empty
                      <tr>
                          <td colspan="2">No setting found</td>
                      </tr>
                  @endforelse
              </tbody>
          </table>
      </div>
      <div id="astro_api" class="tab-pane fade in {{ $tab == 'Api' ? 'active show' : $falseClass }}">
          <table class="table table-hover">
              <tbody>
                  @forelse ($settings->whereIn('key',  $astroapi) as $item)
                    @if(in_array($item->key, $astroapi))
                        @if($item->type == 'image')
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <div class="row" id="image-gallery">
                                <div class="form-group col-4">
                                    <div class="">
                                       <button type="button" class="btn btn-sm mx-2"  data-toggle="modal" onclick="openMediaManager('{{$item->key}}')">Upload Image</button>
                                       <input type="hidden" id="{{$item->key}}_id" name="{{$item->key}}" value="{{$item->value}}">
                                       <input type="hidden" id="{{$item->key}}_src" name="{{$item->key}}_src" value="{{$item->{$item->key.'Fn'} ? $item->{$item->key.'Fn'}->image(200,200) : '' }}">
                                      <div class="{{$item->key}}_image float-right">
                                        <img src="{{$item->{$item->key.'Fn'} ? $item->{$item->key.'Fn'}->image(200,200) : ''}}" short_name="icon" class="img-responsive" style="width:100px;height:100px">
                                        <i class="fa fa-trash text-danger" onclick="removeImage(this, '{{$item->key}}')"></i>
                                      </div>
                                    </div>
                                </div>
                              </div>
                            </td>
                          </tr>
                        @else
                          <tr>
                            <td>{{$item->name}}</td>
                            <td>
                              <input type="{{$item->type}}" name="{{$item->key}}" value="{{$item->value}}" class="form-control">
                            </td>
                          </tr>
                        @endif
                      @endif
                  @empty
                      <tr>
                          <td colspan="2">No setting found</td>
                      </tr>
                  @endforelse
              </tbody>
          </table>
      </div>
    </div>
    <div class="text-center">
      <button type="submit" class="btn btn-primary">Update</button>
    </div>
  </form>
@endsection
@include('admin.media.manager')

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
<script type="text/javascript">
function validateAndSubmitPageFunction(that){
  $("input[type=checkbox]").each(function(){
    let that = this;
    if($(that).is(":checked")){
      $("#"+($(that).attr("data-id"))).val(1);
    }
  });
  return validateAndSubmit(that);
}
var media_image_type = 'setting';
var imageset = 'featured';

$('#insert-image').on('click', function(){
if($('.img-box-choice.selected').length==0) {
    toastr.warning('Please select an image');
  return false;
}
var selectedImg = $('.img-box-choice.selected');
var mediaId = selectedImg.data('mediaid');
var imgSrc = $('.img-box-choice.selected img').attr('src')
var imgSrcOriginal = $('.img-box-choice.selected img').data('original')
var newImg  = selectedImg.clone()
if(imageset==='featured'){
  $('#image_id').val(mediaId);
  $('#image_src').val(imgSrc);
  $('.profile_image').remove();
  newImg.find('.action').remove();
  newImg.removeClass('img-box-choice selected').addClass('profile_image float-right');
  newImg.append('<i class="fa fa-trash text-danger" onclick="removeImage(this, \'image_id\')"></i>');
  $('#image_id').after(newImg)
}
// else if(imageset==='slide'){
//     myVue.updateSideImage(mediaId, imgSrc.replace('200-200/', ''));
// }
else if(imageset=='editor') {
  tinyMCE.activeEditor.execCommand( 'mceInsertContent', true , '<img src="'+imgSrcOriginal.replace(window.location.origin,'')+'" alt="editor-insert-image"/>' );
  imageset = 'featured';
}else{
  $('#'+imageset+'_id').val(mediaId);
  $('#'+imageset+'_src').val(imgSrc);
  $('.'+imageset+'_image').remove();
  newImg.find('.action').remove();
  newImg.removeClass('img-box-choice selected').addClass(imageset+'_image float-right');
  newImg.append('<i class="fa fa-trash text-danger" onclick="removeImage(this, \''+imageset+'\')"></i>');
  $('#'+imageset+'_id').after(newImg)
}
$('#media-img-manager').modal('hide');
});
var removeImage = function(elm, image){
    $(elm).parents('.'+image+'_image').remove();
    // $(elm).parents().remove();
    $('#'+image+'_id').val('');
    $('#'+image+'_src').val('');
 }
 var openMediaManager = function(image = null) {
 imageset = image;
 $('#media-img-manager').modal('show');
 }
</script>
@endpush
