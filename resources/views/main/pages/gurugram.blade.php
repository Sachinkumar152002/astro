@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
@endpush
@section('content')
<section class="about ">
    <div class="container">
        <div class="upper-content">
            <div class="title-4 text-center">
                <h1><span>Skill Development Center Gurugram</span></h1>
            </div>
            <h3 class="text-center">Centre Details</h3>
            <p class="text-center"> Our skill development center in Gurugram is situated in village Harchandpur, district Sohna. It is not only the education but the outcome of education in empowering rural people is what we focus on. Our experts here provide skill development
                training in the field of security and domestic data entry operator. Skill Development Programs Offered - <span> We are successfully running two Skill Development Courses at Harchandpur:- </span></p>
        </div>
        <div class="inner-pages">
            <div class="row">

                <div class="col-md-12 sep-div">
                    <h3 class="title-3 titletxt" style="margin-top:0">Training Programs:- </h3>

                    <div class="col-md-3 col-xs-12 fl-left">
                        <img src="{{asset('images/Tops/Tops-2.jpg')}}" alt="Domestic-Data-Entry-Operator" class="img-responsive">
                    </div>
                    <div class="col-md-9 col-xs-12 fl-right">

                        <h4>1 - Domestic Data Entry Operator</h4>
                        <p>There is no dearth of work for computer operators in the upcoming digital era. Data Entry Operators are not only supposed to handle upcoming work but also computerize a huge amount of existing data. In India after the advent of
                            Digital India Campaign, a Domestic Data Entry Operator can find jobs anywhere ranging from little grocery shops, to printing out bills for big corporate offices to handle daily official work like sending communiqué and drafting
                            letters etc. to maintaining attendance sheets and making presentations in various applications.</p>
                        <p> Here skill experts train the career aspirants on corporate readiness which includes soft and hard skills. The courseware of this Skill Development Program shall equip the trainees with the requisite knowledge and skills to undertake
                            electronic entry of data. On successful completion of the Skill Development Program, the students are provided government-affiliated certifications. <span> Job prospects: Data Entry Operator, Desk Operator, Data Analyst, etc.</span></p>


                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="col-md-12 sep-div">

                    <div class="col-md-3 col-xs-12 fl-right">
                        <img src="{{asset('images/Tops/Tops-3.png')}}" alt="Unarmed-Security-Guards" class="img-responsive">
                    </div>
                    <div class="col-md-9 col-xs-12 fl-left">
                        <h4>2 - Unarmed Security Guards</h4>
                        <p> The Skill Development Course of unarmed security guards require minimal formal education, a high school diploma or equivalent qualification is enough. The unarmed security guards perform an important role in various industries
                            all over the world. The students are trained to become a public safety officer appointed to keep an eye and maintain a record of the visitors and also work as the protector of a property</p>
                        <p> <span> Job prospects: *They can work in hospitals, power plants, retail stores, corporate offices etc.</span> </p>
                    </div>

                    <div class="clearfix"></div>
                </div>

                <div class="col-md-12 sep-div">
                    <h3 class="title-3 titletxt" style="margin-top:0">Address</h3>

                    <div class="col-md-5 col-xs-12 fl-left">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7028.127799965102!2d77.13470397540921!3d28.26607918025502!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390d26372e6fc8db%3A0x439824495aea3881!2sHarchandpur%2C+Haryana+122102!5e0!3m2!1sen!2sin!4v1525082335169" width="400" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div>
                    <div class="col-md-7 col-xs-12 fl-right">
                        <p>Government Senior Secondary School, Village – Harchandpur, Distt- Sohna Gurugram-122103</p>

                        <div class="row">
                            <div class="col-md-10 col-xs-12">
                                <p class=""><i class="fa fa-globe"></i> <a href="#" target="_blank"> info@smartskilltech.com</a> </p>
                                <div class="row">
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="thumbnail">
                                            <img src="{{asset('images/Harchandpur-1.jpg')}}" alt="Skill-Development-Center-Gurugram-Harchandpur" class="img-responsive">
                                            <div class="caption">
                                                <p>Harchandpur </p>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="thumbnail">
                                            <img src="{{asset('images/Harchandpur-2.jpg')}}" alt="Skill-Development-Center-Gurugram-Harchandpur" class="img-responsive">
                                            <div class="caption">
                                                <p>Harchandpur </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="thumbnail">
                                            <img src="{{asset('images/Harchandpur-3.jpg')}}" alt="Skill-Development-Center-Gurugram-Harchandpur" class="img-responsive">
                                            <div class="caption">
                                                <p>Harchandpur</p>
                                            </div>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="clearfix"></div>
            </div>


        </div>
    </div>
</section>
@endsection