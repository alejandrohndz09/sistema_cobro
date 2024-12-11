<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-mg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0 ">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h3 class="text-dark" id="titulo">Nueva cuota</h3>
                        <p class="mb-0">(*) Campos Obligatorios</p>
                    </div>
                    <div class="card-body">
                        <form role="form text-left" id="CuotaForm">
                            @csrf
                            <input type="hidden" name="_method" id="method" value="POST"> <!-- Para edición -->
                            <input type="hidden" name="idVenta" id="idVenta" value="{{ $ventaData->idVenta }}">
                            <div class="row mb-4">

                                <label>Fecha pago: *</label>
                                <div class="input-group mb-1">
                                    <input type="date" name="fechaPago" id="fechaPago" class="form-control"
                                        placeholder="Fecha de pago" autocomplete="off">
                                </div>
                                <span id="error-fechaPago" class="text-danger text-xs mb-3"></span>

                                {{-- <label>Fecha límite: *</label>
                                <div class="input-group mb-1">
                                    <input type="date" name="fechaLimite" id="fechaLimite" class="form-control"
                                        placeholder="Fecha límite" autocomplete="off">
                                </div>
                                <span id="error-fechaLimite" class="text-danger text-xs mb-3"></span>

                                <label>Monto: *</label>
                                <div class="input-group mb-1">
                                    <input type="number" name="monto" id="monto" class="form-control"
                                        placeholder="Monto" autocomplete="off" step="0.01" min="0">
                                </div>
                                <span id="error-monto" class="text-danger text-xs mb-3"></span>

                                <label>Mora: *</label>
                                <div class="input-group mb-1">
                                    <input type="number" name="mora" id="mora" class="form-control"
                                        placeholder="Mora" autocomplete="off" step="0.01" min="0">
                                </div>
                                <span id="error-mora" class="text-danger text-xs mb-3"></span> --}}

                            </div>

                            <div class="text-end">
                                <button type="reset" data-bs-dismiss="modal" style="border-color:transparent"
                                    class="btn btn-outline-dark btn-sm mt-4 mb-0">
                                    <i class="fas fa-undo text-xs"></i>&nbsp;&nbsp;Cancelar</button>
                                <button type="submit" class="btn btn-icon bg-gradient-success btn-sm mt-4 mb-0">
                                    <i class="fas fa-check text-xs"></i>&nbsp;&nbsp;Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
