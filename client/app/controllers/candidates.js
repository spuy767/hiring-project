import Controller from '@ember/controller';
import { action } from '@ember/object';
import { tracked } from '@glimmer/tracking';
import { inject as service } from '@ember/service';

export default class CandidatesController extends Controller {
  @service store;
  @service router;
  @tracked candidate = {
    name: '',
    age: '',
  };

  @tracked applicant = this.store.createRecord('applicant', {});

  @action
  reset() {
    this.candidate = {
      name: '',
      age: '',
    };
    this.model = this.store.query('applicant', {});
    // I'm sure there is a more elegant way to do this, but the latest ember docs reference router.refresh which doesn't appear to exist in this version of Ember
  }

  @action
  async addNew() {
    this.applicant = this.store.createRecord('applicant', this.candidate);
    this.applicant.save().then(this.reset.bind(this), () => {});
  }
}
