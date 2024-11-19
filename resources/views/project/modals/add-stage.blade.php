  <!-- Add Stage Modal -->
  <div class="modal fade  text-gray-900" id="addStageModal" tabindex="-1" aria-labelledby="addStageModalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <form id="addStageForm" method="POST" action="{{ route('project.addStage', $project->id) }}">
                  @csrf
                  <div class="modal-header">
                      <h5 class="modal-title" id="addStageModalLabel">{{ __('Add Stage') }}</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="mb-3">
                          <label for="name" class="form-label">{{ __('Stage Name') }}</label>
                          <input type="text" class="form-control" id="name" name="name" required>
                      </div>
                      <div class="mb-3">
                          <label for="description" class="form-label">{{ __('Description') }}</label>
                          <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                      </div>
                      <div class="mb-3">
                          <label for="start_date" class="form-label">{{ __('Start Date') }}</label>
                          <input type="date" class="form-control" id="start_date" name="start_date" required>
                      </div>
                      <div class="mb-3">
                          <label for="end_date" class="form-label">{{ __('End Date') }}</label>
                          <input type="date" class="form-control" id="end_date" name="end_date" required>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary"
                          data-bs-dismiss="modal">{{ __('Close') }}</button>
                      <button type="submit" class="btn btn-primary">{{ __('Add Stage') }}</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
