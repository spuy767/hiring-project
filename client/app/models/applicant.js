import Model, { attr } from '@ember-data/model';

export default class ApplicantModel extends Model {
  @attr('string') name;
  @attr('number') age;
}
