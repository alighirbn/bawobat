  <!-- Add Investor Modal -->
  <div class="modal fade  text-gray-900" id="addInvestorModal" tabindex="-1" aria-labelledby="addInvestorModalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <form id="addInvestorForm" method="POST" action="{{ route('project.addInvestor', $project->id) }}">
                  @csrf
                  <div class="modal-header">
                      <h5 class="modal-title" id="addInvestorModalLabel">{{ __('Add Investor') }}
                      </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="mb-3">
                          <label for="investor_id" class="form-label">{{ __('Select Investor') }}</label>
                          <select class="form-select" id="investor_id" name="investor_id" required>
                              <option value="" disabled selected>{{ __('Choose an investor') }}
                              </option>
                              @foreach ($investors as $investor)
                                  <option value="{{ $investor->id }}">{{ $investor->name }}</option>
                              @endforeach
                          </select>
                      </div>
                      <div class="mb-3">
                          <label for="investment_amount" class="form-label">{{ __('Investment Amount') }}</label>
                          <input type="number" class="form-control" id="investment_amount" name="investment_amount"
                              min="1" required>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary"
                          data-bs-dismiss="modal">{{ __('word.close') }}</button>
                      <button type="submit" class="btn btn-primary">{{ __('word.add_investor') }}</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
