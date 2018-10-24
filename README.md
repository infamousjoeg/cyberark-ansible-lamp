# CyberArk & Ansible - Secured LAMP Stack Deployment

This is an example of how to deploy a LAMP Stack using [Ansible Tower](https://www.ansible.com/license) secured bi-directionally with [CyberArk PAS Core & Application Identity Manager (AIM)](https://cyberark.com) and, separately, [CyberArk Conjur](https://conjur.org).

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### YouTube Video Demonstration & Walkthrough

[![CyberArk & Ansible - AIM Bi-Directional Integration](https://img.youtube.com/vi/PHT76FYLNbY/0.jpg)](https://www.youtube.com/watch?v=PHT76FYLNbY)

### Prerequisites

* Red Hat Ansible Tower
  * Installed on Red Hat Enterprise Linux (RHEL) 7.5
* Red Hat Enterprise Linux (RHEL) 7.5 Virtual Machine Host
* CyberArk Privileged Account Security (PAS) Core v10.x
* CyberArk Application Identity Manager (AIM) v10.x
* [CyberArk Conjur Open Source](https://conjur.org) -OR- CyberArk Conjur Enterprise Edition v5.x
* `cyberark.modules` role from Ansible Galaxy
  * `$ ansible-galaxy install cyberark.modules`

### Configuration

#### Setup in CyberArk EPV

1. Create an Application ID for Ansible.
2. Create a safe for storing your Private Keys for access to hosts.
3. Create a safe for storing your REST API account for Ansible to use.
4. Create a safe for storing your MySQL database user created during deployment.
5. Permission your Ansible Application ID, AIMWebService for AIM CCP, and PROV_ provider user to each safe.  Be sure to grant just default permissions for each safe except where the MySQL database user will be onboarded.  That should include additional permissions on the Ansible Application ID to allow adding accounts and updating properties.
6. Create an Application ID for your PHP Webapp.
7. Permission your PHP Webapp Application ID to the MySQL database user safe with default permissions for later fetching via AIM CCP.
8. In Platform Management for the MySQL platform that will be used, optionally enable "AutoChangeOnAdd" in the "UI & Workflows" general section.  This will change the password of the MySQL User Object upon onboarding to the safe.

#### Setup in Ansible Tower

1. Create a Project pointing to this repository.
2. Create a Job Template targeting the Inventory to deploy the LAMP Stack.  Use the `aim.yml` playbook.  Add a `Dummy Credential` with no user/pass to the Job Template since it's a requirement.
3. Make sure the values in `aim.yml` match your environment.
4. Launch the Job!

#### Setup in CyberArk Conjur

1. In the `./policies/` directory are the policies to be loaded into CyberArk Conjur.
2. `conjur policy load root root.yml`
3. `conjur policy load ansible-tower ansible-tower.yml`
4. Add the Host ID and API Key to a custom credential type for CyberArk Conjur Machine Identity. (This will be provided later here)
5. `conjur policy load helloworldphp helloworldphp.yml`
6. After loading the policies, login to the Conjur UI and intialize each secret created with a value.  For the Private Key secret that is created, copy the contents of the Private Key from EPV into the Secret Manager.

#### Setup in Ansible Tower

1. Create a Project pointing to this repository.
2. Create a Job Template targeting the Inventory to deploy the LAMP Stack.  Use the `conjur.yml` playbook.  Add the `CyberArk Conjur Machine Identity` credential to the Job Template.
3. Make sure the values in `conjur.yml` match your environment.
4. Launch the Job!

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/your/project/tags). 

## Authors

* **Joe Garcia, CISSP** - *Initial release* - [InfamousJoeG](https://github.com/InfamousJoeG)

See also the list of [contributors](https://github.com/infamousjoeg/cyberark-ansible-lamp/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details