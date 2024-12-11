<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-mg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h3 class="text-dark" id="titulo">Agregar Usuario</h3>
                        <p class="mb-0">(*) Campos Obligatorios</p>
                    </div>
                    <div class="card-body">
                        <form id="usuarioForm" method="POST">
                            @csrf
                            <input type="hidden" name="_method" id="method" value="POST">
                            
                            <!-- Campo Usuario -->
                            <div class="form-group">
                                <label for="usuario">Usuario: *</label>
                                 <div class="input-group mb-3">
                                 <input type="text" name="usuario" id="usuario" class="form-control" placeholder="Nombre de usuario" required>
                                 </div>
                                 <small id="error-usuario" class="form-text text-danger"></small>
                            </div>

                            <div class="form-group">
                               <label for="email">Correo: *</label>
                                  <div class="input-group mb-3">
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Agregue su correo" required>
                                  </div>
                                    <small id="error-email" class="form-text text-danger"></small>
                            </div>


                           <!-- Campo ID Empleado -->
                             <label for="idEmpleado">Empleado: *</label>
                             <div class="input-group mb-1">
                                <select name="idEmpleado" id="idEmpleado" class="form-control" required>
                                <option value="">Seleccione un empleado</option>
                                @foreach($empleados as $empleado)
                                <option value="{{ $empleado->idEmpleado }}">{{ $empleado->nombres . ' ' . $empleado->apellidos }}</option>
                                @endforeach
                                </select>
                              </div>

                            

                            <div class="text-end">
                                <button type="button" data-bs-dismiss="modal" style="border-color:transparent" class="btn btn-outline-dark btn-sm mt-4 mb-0">
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

<div class="modal fade" id="modalConfirm" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-mg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0 ">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h3 class="text-dark">Confirmar operación</h3>
                        <p id="dialogo" class="mb-0">Está a punto de deshabilitar el registro. ¿Desea continuar?</p>
                    </div>
                    <div class="card-body">
                        <form role="form text-left" id="confirmarForm">
                            @csrf
                            <input type="hidden" id="methodC">
                           <div class="text-end">
                                <button type="reset" data-bs-dismiss="modal"
                                    style="border-color:transparent" class="btn btn-outline-dark btn-sm mt-4 mb-0">
                                    <i class="fas fa-times text-xs"></i>&nbsp;&nbsp;No</button>
                                <button type="submit" class="btn btn-icon bg-gradient-danger btn-sm mt-4 mb-0">
                                    <i class="fas fa-check text-xs"></i>&nbsp;&nbsp;Sí</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>