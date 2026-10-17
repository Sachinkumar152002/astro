<form onsubmit="return validateSubmit(this);" role="form" action="{{route('contact.submit')}}" method="post" name="form1">
    {{csrf_field()}}
    <input type="hidden" name="source" value="2">
    <input type="hidden" name="for" value="Japanese Work Culture">
    <input type="hidden" name="message" value="Japanese Work Culture">
    <div class="form-group">
    <label>Name</label>
    <input type="text" class="form-control"  name="name" placeholder="Name" required>
    </div>
    <div class="form-group">
    <label>Email address</label>
    <input type="email" class="form-control"  name="email" placeholder="Email" required>
    </div>
    <div class="form-group">
    <label>Phone No</label>
    <input type="text" class="form-control"  name="phone" placeholder="Phone" required>
    </div>

    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
</form>

<form role="form" action="{{route('contact.submit')}}" method="post" id="contact-form" novalidate="novalidate">
    {{csrf_field()}}
    <div class="form-group">
    <label>Name</label>
    <input class="form-control" id="contact_name" name="name" placeholder="Name" required="" type="text">
    </div>
    <div class="form-group">
    <label>Email address</label>
    <input class="form-control" id="contact_email" name="email" placeholder="Email here" required="" type="email">
    </div>
    <div class="form-group">
    <label>Phone No</label>
    <input class="form-control" id="contact_mobile" name="phone" placeholder="Phone" required="" type="text">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>