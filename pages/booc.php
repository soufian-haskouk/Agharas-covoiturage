    <?php include('templates\header.php'); ?>
<!-- book section -->

<section class="book_section">
    <div class="container">
      <div class="col-md-10 col-lg-8 mx-auto px-0">
        <div class="book_form">
          <form>
            <div class="form-row">
              <div class="form-group col-md-6">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fa fa-map-marker" aria-hidden="true"></i>
                    </div>
                  </div>
                  <input type="text" class="form-control" id="inputPickupLocation" placeholder="Depart">
                </div>
              </div>
              <div class="form-group col-md-6">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fa fa-map-marker" aria-hidden="true"></i>
                    </div>
                  </div>
                  <input type="text" class="form-control" id="inputDropLocation" placeholder="Arriver">
                </div>
              </div>
            </div>
            <div class="btn-box">
              <button type="submit" class="btn btn-primary">Reserve</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</section>
<!-- end book section -->