import {Address} from "./address";

export class Profile {
  id!: number;
  first_name!: string;
  last_name!: string;
  phone!: string;
  address!: Address;
  email!: string;
  totp_enabled!: boolean;
}
