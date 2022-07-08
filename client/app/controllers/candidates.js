import Controller from '@ember/controller';
import { action } from '@ember/object';
import { tracked } from '@glimmer/tracking';
import { inject as service } from '@ember/service';

export default class CandidatesController extends Controller {
  @service store;
  @tracked candidate = {
    name: '',
    age: '',
  };

  @action
  async addNew() {
    const applicant = this.store.createRecord('applicant', this.candidate);
    applicant.save().then(
      () => window.location.reload(),
      (e) => console.log(e) || console.log(this.model.get('errors.age'))
    );
  }
}
