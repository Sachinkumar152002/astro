@extends('layouts.app')

@section('content')
    @include('main.partials.breadcrumb')
    <section class="about ">
        <div class="container">
            <div class="upper-content">
                <div class="title-4 text-center">
                    <h1><span> PMKVY courses at Smart Skill Tech.</span></h1>
                </div>
                <h3 class="text-center"> What is PMKVY? </h3>
                <p class="text-center"> Pradhan Mantri Kaushal Vikas Yojana (PMKVY) is the flagship scheme of the
                    Ministry of Skill Development & Entrepreneurship (MSDE). The objective of this Skill Certification
                    Scheme is to enable a large number of Indian youth to take up industry-relevant
                    skill training that will help them in securing a better livelihood. Individuals with prior learning
                    experience or skills will also be assessed and certified under Recognition of Prior Learning
                    (RPL).</p>

            </div>
            <div class="inner-pages">
                <div class="row">

                    <div class="col-md-12 sep-div">
                        <h3 class="title-3 titletxt" style="margin-top:0">Vision-</h3>

                        <div class="col-md-3 col-xs-12 fl-left">
                            <img src="{{asset('images/mission.jpg')}}" alt="mission" class="img-responsive">
                        </div>
                        <div class="col-md-9 col-xs-12 fl-right">
                            <p>Skilling people, enhancing livelihoods, transforming lives</p>

                            <h4 class="title-3 titletxt" style="margin-top:0">Mission-</h4>
                            <p> We will build an Inclusive India by providing locally and globally relevant skills and
                                attitudes to 1million learners for their lifelong employability.Skill Development
                                Centers</p>
                            <p>Smart Skill Tech. provides Skill Development Programs under PMKVY in various skill
                                sectors like security, apparels, data entry operator, painters and decorators, building
                                and construction management etc. Currently, Smart Skill
                                Tech is successfully running its Skill Development centres in Wardha, Nagpur, Pune and
                                Faridabad.</p>
                            <p> The group has dedicated itself to facilitate Skill Development Programs on both online
                                and offline mode to join the Skill India Mission in various sectors. Our placement
                                assistance services add value to the courses. It is not only
                                the education but the outcome of education in empowering rural people is what we are
                                focusing for. Smart Skill Tech has set up various Skill Development Training centers to
                                facilitate Skill Development in rural areas.</p>
                            <p> Smart Skill Tech has two working Skill Development centres in Faridabad in which we
                                provide Skill Development Programs under PMKVY in various skill sectors. Skill Training
                                in Faridabad has been set up to facilitate the development
                                and upgrading the skills of the student and the growing workforce through our Skill
                                Development Programs.</p>

                        </div>
                        <div class="clearfix"></div>
                    </div>


                    <div class="col-md-12 sep-div">
                        <h4 class="title-3 titletxt" style="margin-top:0">Skill Development Centre at Faridabad is
                            offering these Skill Development Programs:-</h4>

                        <div class="col-md-3 col-xs-12 fl-right">
                            <img src="{{asset('images/computers-lab.jpg')}}" class="img-responsive">
                        </div>
                        <div class="col-md-9 col-xs-12 fl-left">
                            <h4> 1. Plot No. 137, Sec.24</h4>
                            <p>Here skill development experts train the aspirants in the vocational course of Sewing
                                Machine Training (Tailoring), it one of the job oriented development course. The best
                                thing about this course is that it suits female candidates
                                very well.</p>
                            <h4> 2. D-541, Sec.15A </h4>
                            <p> Here skill development trainers strive to ensure that the young aspirants are trained on
                                corporate readiness. It helps in bridging the gap between academia and industry
                                demands.</p>

                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="col-md-12 sep-div">
                        <h4 class="title-3 titletxt" style="margin-top:0"> Skill Development Centre at
                            Harchandpur(Gurugram) offers these Skill Development Programs :-</h4>

                        <div class="col-md-3 col-xs-12 fl-left">
                            <img src="{{asset('images/Data-Entry-Operator-Jobs.jpg')}}" class="img-responsive">
                        </div>
                        <div class="col-md-9 col-xs-12 fl-right">
                            <h4> 1. Domestic Data Entry Operator</h4>
                            <p>There is no dearth of work for computer operators in the upcoming digital era. Data Entry
                                Operators are not only supposed to handle upcoming work but also, computerize a huge
                                amount of existing data. In India after the advent of
                                Digital India Campaign, a Domestic Data Entry Operator can find jobs anywhere ranging
                                from little grocery shops to printing out bills for big corporate offices to handle
                                daily official work like sending communiqué and drafting
                                letters etc. to maintaining attendance sheets and making presentations in various
                                applications.</p>
                            <p> Here skill experts train the career aspirants on corporate readiness which includes soft
                                and hard skills. The courseware of this Skill Development The program shall equip the
                                trainees with the requisite knowledge and skills to
                                undertake electronic entry of data. On successful completion of the Skill Development
                                Program, the students are provided government-affiliated certifications. <span> Job prospects: Data Entry Operator, Desk Operator, Data Analyst, etc.</span>
                            </p>

                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="col-md-12 sep-div">

                        <div class="col-md-3 col-xs-12 fl-right">
                            <img src="{{asset('images/Tops/Tops-4.jpg')}}" alt="unarmes-security-guards"
                                 class="img-responsive">
                        </div>
                        <div class="col-md-9 col-xs-12 fl-left">
                            <h4> 2. Unarmed Security Guards</h4>
                            <p>The Skill Development Course of unarmed security guards requires minimal formal
                                education, a high school diploma or equivalent qualification is enough. The unarmed
                                security guards perform an important role in various industries
                                all over the world. The students are trained to become a public safety officer appointed
                                to keep an eye and maintain a record of the visitors and also work as the protector of a
                                property. <span> Job prospects: They can work in hospitals, power plants, retail stores, corporate offices etc.</span>
                            </p>

                        </div>
                        <div class="clearfix"></div>
                    </div>


                    <div class="clearfix"></div>
                </div>


            </div>
        </div>
    </section>
@endsection