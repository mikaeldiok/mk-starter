<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="float-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editCommitment">
                    Edit
                </button>
            </div>
        </div>
    </div>
    @if($donator->commitments->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nominal</th>
                        <th>Jangka waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $commitment = $donator->commitments->first();
                    ?>
                    <tr>
                        <td>
                            Rp. {{number_format($commitment->amount, 2, ',', '.')}}                              
                        </td>
                        <td>
                            Tiap {{ $commitment->periods}} bulan
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    @else
        <p class="lead">
            Kami akan membantu ada supaya dapat tetap berkomitmen dalam mendukung Gerakan Peduli Pendidikan YPW!
        </p>
        <small>
            Isikan nominal dan berapa bulan sekali anda ingin berdonasi
        </small>
        @include("benefactor::frontend.commitments.create")
    @endif
</div>