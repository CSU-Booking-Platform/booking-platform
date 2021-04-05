export let bookTest = [
  {
    "id": 5,
    "user_id": 1,
    "status": "approved",
    "created_at": "2021-03-28T17:52:29.000000Z",
    "updated_at": "2021-03-28T17:52:29.000000Z",
    "reference": [],
    "log": null,
    "onsite_contact": {
      "name": "Gilberto Kuvalis",
      "email": "montana.jacobson@yahoo.com",
      "phone": "(438) 315-9854 x8903"
    },
    "event": {
      "type": "praesentium",
      "title": "et",
      "attendees": 296670193,
      "description": "Et voluptatibus dolor nostrum et voluptatem. Nihil et eum maxime aut qui sint. Explicabo ipsum dolorem occaecati aut fugiat.",
      "guest_speakers": "Favian Wyman"
    },
    "notes": "Reprehenderit corrupti pariatur ut occaecati rem. Est itaque iusto non vero. Officiis corrupti eligendi nihil voluptatem.\n\nQuis quia doloremque magnam omnis. Voluptas reiciendis quam architecto ea libero aut doloribus. Animi libero inventore ducimus et. Amet iste excepturi aut.\n\nQuis cumque cum odit rem maiores. Velit repellendus nihil ratione eaque aliquam in.",
    "room": {
      "id": 1,
      "name": "CSU Lounge",
      "number": "H-709",
      "floor": 7,
      "building": "Hall",
      "created_at": "2021-03-28T17:52:28.000000Z",
      "updated_at": "2021-03-28T17:52:28.000000Z",
      "status": "available",
      "min_days_advance": 10,
      "max_days_advance": 25,
      "attributes": {
        "food": true,
        "sofas": 3,
        "chairs": 0,
        "tables": 0,
        "alcohol": true,
        "computer": false,
        "projector": false,
        "fundraiser": false,
        "television": false,
        "whiteboard": false,
        "a_v_permitted": true,
        "ambiant_music": false,
        "coffee_tables": 3,
        "sale_for_profit": false,
        "capacity_sitting": "150",
        "capacity_standing": "450"
      },
      "room_type": "Lounge",
      "pivot": {
        "booking_request_id": 5,
        "room_id": 1,
        "created_at": "2021-03-28T17:52:29.000000Z",
        "updated_at": "2021-03-28T17:52:29.000000Z"
      }
    },
    "requester": {
      "id": 1,
      "name": "Joseph doe",
      "email": "admin@email.com",
      "email_verified_at": "2021-03-28T17:52:28.000000Z",
      "current_team_id": null,
      "profile_photo_path": null,
      "created_at": "2021-03-28T17:52:28.000000Z",
      "updated_at": "2021-03-28T17:52:28.000000Z",
      "phone": "341-262-3829",
      "organization": "Batz-Heidenreich",
      "profile_photo_url": "https://ui-avatars.com/api/?name=Joseph+doe&color=7F9CF5&background=EBF4FF",
      "can": ["users.select", "users.select.same-role", "users.create", "users.update", "users.delete", "roles.assign", "roles.create", "roles.update", "roles.delete", "bookings.create", "bookings.update", "bookings.approve", "bookings.delete", "rooms.create", "rooms.update", "rooms.delete", "rooms.blackouts.create", "rooms.blackouts.update", "rooms.blackouts.delete", "settings.edit"],
      "permissions": [],
      "roles": [{
        "id": 1,
        "name": "super-admin",
        "guard_name": "web",
        "created_at": "2021-03-28T17:52:28.000000Z",
        "updated_at": "2021-03-28T17:52:28.000000Z",
        "number_of_bookings_per_period": null,
        "number_of_days_per_period": null,
        "pivot": {"model_id": 1, "role_id": 1, "model_type": "App\\Models\\User"},
        "permissions": [{
          "id": 1,
          "name": "users.select",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 1}
        }, {
          "id": 2,
          "name": "users.select.same-role",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 2}
        }, {
          "id": 3,
          "name": "users.create",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 3}
        }, {
          "id": 4,
          "name": "users.update",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 4}
        }, {
          "id": 5,
          "name": "users.delete",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 5}
        }, {
          "id": 6,
          "name": "roles.assign",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 6}
        }, {
          "id": 7,
          "name": "roles.create",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 7}
        }, {
          "id": 8,
          "name": "roles.update",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 8}
        }, {
          "id": 9,
          "name": "roles.delete",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 9}
        }, {
          "id": 10,
          "name": "bookings.create",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 10}
        }, {
          "id": 11,
          "name": "bookings.update",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 11}
        }, {
          "id": 12,
          "name": "bookings.approve",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 12}
        }, {
          "id": 13,
          "name": "bookings.delete",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 13}
        }, {
          "id": 14,
          "name": "rooms.create",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 14}
        }, {
          "id": 15,
          "name": "rooms.update",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 15}
        }, {
          "id": 16,
          "name": "rooms.delete",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 16}
        }, {
          "id": 17,
          "name": "rooms.blackouts.create",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 17}
        }, {
          "id": 18,
          "name": "rooms.blackouts.update",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 18}
        }, {
          "id": 19,
          "name": "rooms.blackouts.delete",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 19}
        }, {
          "id": 20,
          "name": "settings.edit",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 20}
        }]
      }]
    },
    "reservations": [{
      "id": 5,
      "room_id": 1,
      "booking_request_id": 5,
      "start_time": "2021-04-01T12:46:00.000000Z",
      "end_time": "2021-04-01T14:46:00.000000Z",
      "created_at": "2021-03-28T17:52:29.000000Z",
      "updated_at": "2021-03-28T17:52:29.000000Z",
      "room": {
        "id": 1,
        "name": "CSU Lounge",
        "number": "H-709",
        "floor": 7,
        "building": "Hall",
        "created_at": "2021-03-28T17:52:28.000000Z",
        "updated_at": "2021-03-28T17:52:28.000000Z",
        "status": "available",
        "min_days_advance": 10,
        "max_days_advance": 25,
        "attributes": {
          "food": true,
          "sofas": 3,
          "chairs": 0,
          "tables": 0,
          "alcohol": true,
          "computer": false,
          "projector": false,
          "fundraiser": false,
          "television": false,
          "whiteboard": false,
          "a_v_permitted": true,
          "ambiant_music": false,
          "coffee_tables": 3,
          "sale_for_profit": false,
          "capacity_sitting": "150",
          "capacity_standing": "450"
        },
        "room_type": "Lounge"
      }
    }]
  },
  {
    "id": 6,
    "user_id": 1,
    "status": "approved",
    "created_at": "2021-03-28T17:52:29.000000Z",
    "updated_at": "2021-03-28T17:52:29.000000Z",
    "reference": [],
    "log": null,
    "onsite_contact": {
      "name": "Gilberto Kuvalis",
      "email": "montana.jacobson@yahoo.com",
      "phone": "(438) 315-9854 x8903"
    },
    "event": {
      "type": "praesentium",
      "title": "et",
      "attendees": 296670193,
      "description": "Et voluptatibus dolor nostrum et voluptatem. Nihil et eum maxime aut qui sint. Explicabo ipsum dolorem occaecati aut fugiat.",
      "guest_speakers": "Favian Wyman"
    },
    "notes": "Reprehenderit corrupti pariatur ut occaecati rem. Est itaque iusto non vero. Officiis corrupti eligendi nihil voluptatem.\n\nQuis quia doloremque magnam omnis. Voluptas reiciendis quam architecto ea libero aut doloribus. Animi libero inventore ducimus et. Amet iste excepturi aut.\n\nQuis cumque cum odit rem maiores. Velit repellendus nihil ratione eaque aliquam in.",
    "room": {
      "id": 1,
      "name": "CSU Lounge",
      "number": "H-709",
      "floor": 7,
      "building": "Hall",
      "created_at": "2021-03-28T17:52:28.000000Z",
      "updated_at": "2021-03-28T17:52:28.000000Z",
      "status": "available",
      "min_days_advance": 10,
      "max_days_advance": 25,
      "attributes": {
        "food": true,
        "sofas": 3,
        "chairs": 0,
        "tables": 0,
        "alcohol": true,
        "computer": false,
        "projector": false,
        "fundraiser": false,
        "television": false,
        "whiteboard": false,
        "a_v_permitted": true,
        "ambiant_music": false,
        "coffee_tables": 3,
        "sale_for_profit": false,
        "capacity_sitting": "150",
        "capacity_standing": "450"
      },
      "room_type": "Lounge",
      "pivot": {
        "booking_request_id": 5,
        "room_id": 1,
        "created_at": "2021-03-28T17:52:29.000000Z",
        "updated_at": "2021-03-28T17:52:29.000000Z"
      }
    },
    "requester": {
      "id": 1,
      "name": "Joseph doe",
      "email": "admin@email.com",
      "email_verified_at": "2021-03-28T17:52:28.000000Z",
      "current_team_id": null,
      "profile_photo_path": null,
      "created_at": "2021-03-28T17:52:28.000000Z",
      "updated_at": "2021-03-28T17:52:28.000000Z",
      "phone": "341-262-3829",
      "organization": "Batz-Heidenreich",
      "profile_photo_url": "https://ui-avatars.com/api/?name=Joseph+doe&color=7F9CF5&background=EBF4FF",
      "can": ["users.select", "users.select.same-role", "users.create", "users.update", "users.delete", "roles.assign", "roles.create", "roles.update", "roles.delete", "bookings.create", "bookings.update", "bookings.approve", "bookings.delete", "rooms.create", "rooms.update", "rooms.delete", "rooms.blackouts.create", "rooms.blackouts.update", "rooms.blackouts.delete", "settings.edit"],
      "permissions": [],
      "roles": [{
        "id": 1,
        "name": "super-admin",
        "guard_name": "web",
        "created_at": "2021-03-28T17:52:28.000000Z",
        "updated_at": "2021-03-28T17:52:28.000000Z",
        "number_of_bookings_per_period": null,
        "number_of_days_per_period": null,
        "pivot": {"model_id": 1, "role_id": 1, "model_type": "App\\Models\\User"},
        "permissions": [{
          "id": 1,
          "name": "users.select",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 1}
        }, {
          "id": 2,
          "name": "users.select.same-role",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 2}
        }, {
          "id": 3,
          "name": "users.create",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 3}
        }, {
          "id": 4,
          "name": "users.update",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 4}
        }, {
          "id": 5,
          "name": "users.delete",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 5}
        }, {
          "id": 6,
          "name": "roles.assign",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 6}
        }, {
          "id": 7,
          "name": "roles.create",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 7}
        }, {
          "id": 8,
          "name": "roles.update",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 8}
        }, {
          "id": 9,
          "name": "roles.delete",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 9}
        }, {
          "id": 10,
          "name": "bookings.create",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 10}
        }, {
          "id": 11,
          "name": "bookings.update",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 11}
        }, {
          "id": 12,
          "name": "bookings.approve",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 12}
        }, {
          "id": 13,
          "name": "bookings.delete",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 13}
        }, {
          "id": 14,
          "name": "rooms.create",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 14}
        }, {
          "id": 15,
          "name": "rooms.update",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 15}
        }, {
          "id": 16,
          "name": "rooms.delete",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 16}
        }, {
          "id": 17,
          "name": "rooms.blackouts.create",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 17}
        }, {
          "id": 18,
          "name": "rooms.blackouts.update",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 18}
        }, {
          "id": 19,
          "name": "rooms.blackouts.delete",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 19}
        }, {
          "id": 20,
          "name": "settings.edit",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 20}
        }]
      }]
    },
    "reservations": [{
      "id": 5,
      "room_id": 1,
      "booking_request_id": 5,
      "start_time": "2021-04-01T12:46:00.000000Z",
      "end_time": "2021-04-01T14:46:00.000000Z",
      "created_at": "2021-03-28T17:52:29.000000Z",
      "updated_at": "2021-03-28T17:52:29.000000Z",
      "room": {
        "id": 1,
        "name": "CSU Lounge",
        "number": "H-709",
        "floor": 7,
        "building": "Hall",
        "created_at": "2021-03-28T17:52:28.000000Z",
        "updated_at": "2021-03-28T17:52:28.000000Z",
        "status": "available",
        "min_days_advance": 10,
        "max_days_advance": 25,
        "attributes": {
          "food": true,
          "sofas": 3,
          "chairs": 0,
          "tables": 0,
          "alcohol": true,
          "computer": false,
          "projector": false,
          "fundraiser": false,
          "television": false,
          "whiteboard": false,
          "a_v_permitted": true,
          "ambiant_music": false,
          "coffee_tables": 3,
          "sale_for_profit": false,
          "capacity_sitting": "150",
          "capacity_standing": "450"
        },
        "room_type": "Lounge"
      }
    }]
  },
  {
    "id": 7,
    "user_id": 1,
    "status": "review",
    "created_at": "2021-03-28T17:52:29.000000Z",
    "updated_at": "2021-03-28T17:52:29.000000Z",
    "reference": [],
    "log": null,
    "onsite_contact": {
      "name": "Prof. Bradly Runolfsson",
      "email": "amari.sawayn@hotmail.com",
      "phone": "397-970-4097 x87158"
    },
    "event": {
      "type": "sit",
      "title": "explicabo",
      "attendees": 1672291856,
      "description": "Debitis adipisci eum id commodi nihil corporis animi. Numquam nisi labore sint odit ipsam voluptatem. Hic ea quia et voluptas. Et quia vel id omnis.",
      "guest_speakers": "Luna Pfannerstill"
    },
    "notes": "In quo eveniet nulla et incidunt aspernatur aspernatur. Eaque et at aliquam nihil odio repellendus omnis. Error modi molestias asperiores porro maiores. Vitae quisquam voluptates repellendus inventore saepe.\n\nMagnam aliquam iusto sunt deserunt corrupti nisi ab. Et alias illum assumenda aliquam eveniet maiores qui. Dolorum eligendi reiciendis animi voluptatem dolorum eos sit quis.\n\nUt debitis minima et enim illum. Enim dignissimos ipsum voluptates officiis dolor numquam. Harum soluta sit cum et repudiandae amet tempore quasi. A ut sit veniam commodi laboriosam sit atque beatae.",
    "room": {
      "id": 2,
      "name": "CSU Cafeteria",
      "number": "H-718",
      "floor": 7,
      "building": "Hall",
      "created_at": "2021-03-28T17:52:28.000000Z",
      "updated_at": "2021-03-28T17:52:28.000000Z",
      "status": "available",
      "min_days_advance": 10,
      "max_days_advance": 25,
      "attributes": {
        "food": true,
        "sofas": 0,
        "chairs": 0,
        "tables": 0,
        "alcohol": true,
        "computer": false,
        "projector": false,
        "fundraiser": false,
        "television": false,
        "whiteboard": false,
        "a_v_permitted": true,
        "ambiant_music": false,
        "coffee_tables": 0,
        "sale_for_profit": false,
        "capacity_sitting": "109",
        "capacity_standing": "327"
      },
      "room_type": "Lounge",
      "pivot": {
        "booking_request_id": 7,
        "room_id": 2,
        "created_at": "2021-03-28T17:52:30.000000Z",
        "updated_at": "2021-03-28T17:52:30.000000Z"
      }
    },
    "requester": {
      "id": 1,
      "name": "Joseph doe",
      "email": "admin@email.com",
      "email_verified_at": "2021-03-28T17:52:28.000000Z",
      "current_team_id": null,
      "profile_photo_path": null,
      "created_at": "2021-03-28T17:52:28.000000Z",
      "updated_at": "2021-03-28T17:52:28.000000Z",
      "phone": "341-262-3829",
      "organization": "Batz-Heidenreich",
      "profile_photo_url": "https://ui-avatars.com/api/?name=Joseph+doe&color=7F9CF5&background=EBF4FF",
      "can": ["users.select", "users.select.same-role", "users.create", "users.update", "users.delete", "roles.assign", "roles.create", "roles.update", "roles.delete", "bookings.create", "bookings.update", "bookings.approve", "bookings.delete", "rooms.create", "rooms.update", "rooms.delete", "rooms.blackouts.create", "rooms.blackouts.update", "rooms.blackouts.delete", "settings.edit"],
      "permissions": [],
      "roles": [{
        "id": 1,
        "name": "super-admin",
        "guard_name": "web",
        "created_at": "2021-03-28T17:52:28.000000Z",
        "updated_at": "2021-03-28T17:52:28.000000Z",
        "number_of_bookings_per_period": null,
        "number_of_days_per_period": null,
        "pivot": {"model_id": 1, "role_id": 1, "model_type": "App\\Models\\User"},
        "permissions": [{
          "id": 1,
          "name": "users.select",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 1}
        }, {
          "id": 2,
          "name": "users.select.same-role",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 2}
        }, {
          "id": 3,
          "name": "users.create",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 3}
        }, {
          "id": 4,
          "name": "users.update",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 4}
        }, {
          "id": 5,
          "name": "users.delete",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 5}
        }, {
          "id": 6,
          "name": "roles.assign",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 6}
        }, {
          "id": 7,
          "name": "roles.create",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 7}
        }, {
          "id": 8,
          "name": "roles.update",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 8}
        }, {
          "id": 9,
          "name": "roles.delete",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 9}
        }, {
          "id": 10,
          "name": "bookings.create",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 10}
        }, {
          "id": 11,
          "name": "bookings.update",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 11}
        }, {
          "id": 12,
          "name": "bookings.approve",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 12}
        }, {
          "id": 13,
          "name": "bookings.delete",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 13}
        }, {
          "id": 14,
          "name": "rooms.create",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 14}
        }, {
          "id": 15,
          "name": "rooms.update",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 15}
        }, {
          "id": 16,
          "name": "rooms.delete",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 16}
        }, {
          "id": 17,
          "name": "rooms.blackouts.create",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 17}
        }, {
          "id": 18,
          "name": "rooms.blackouts.update",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 18}
        }, {
          "id": 19,
          "name": "rooms.blackouts.delete",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 19}
        }, {
          "id": 20,
          "name": "settings.edit",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 20}
        }]
      }]
    },
    "reservations": [{
      "id": 7,
      "room_id": 2,
      "booking_request_id": 7,
      "start_time": "2021-04-24T21:21:25.000000Z",
      "end_time": "2021-04-24T23:21:25.000000Z",
      "created_at": "2021-03-28T17:52:30.000000Z",
      "updated_at": "2021-03-28T17:52:30.000000Z",
      "room": {
        "id": 2,
        "name": "CSU Cafeteria",
        "number": "H-718",
        "floor": 7,
        "building": "Hall",
        "created_at": "2021-03-28T17:52:28.000000Z",
        "updated_at": "2021-03-28T17:52:28.000000Z",
        "status": "available",
        "min_days_advance": 10,
        "max_days_advance": 25,
        "attributes": {
          "food": true,
          "sofas": 0,
          "chairs": 0,
          "tables": 0,
          "alcohol": true,
          "computer": false,
          "projector": false,
          "fundraiser": false,
          "television": false,
          "whiteboard": false,
          "a_v_permitted": true,
          "ambiant_music": false,
          "coffee_tables": 0,
          "sale_for_profit": false,
          "capacity_sitting": "109",
          "capacity_standing": "327"
        },
        "room_type": "Lounge"
      }
    }]
  },
  {
    "id": 10,
    "user_id": 1,
    "status": "refused",
    "created_at": "2021-03-28T17:52:30.000000Z",
    "updated_at": "2021-03-28T17:52:30.000000Z",
    "reference": [],
    "log": null,
    "onsite_contact": {"name": "Elton Schowalter", "email": "bauch.arnoldo@yahoo.com", "phone": "(736) 396-8582"},
    "event": {
      "type": "natus",
      "title": "consectetur",
      "attendees": 109061305,
      "description": "Adipisci sit facilis et. Occaecati omnis nostrum omnis enim et exercitationem. Totam temporibus enim earum dicta. Eius voluptatum neque itaque labore aliquid nobis.",
      "guest_speakers": "Shaun Hermiston Jr."
    },
    "notes": "Molestias distinctio quasi explicabo repellat. Unde modi omnis consequatur totam qui omnis. Esse quia est blanditiis illum non eius quia. Qui sit ullam voluptatem repellendus quis molestias quis.\n\nSint totam officiis excepturi repellat quidem dolores sit. Nihil molestiae reprehenderit blanditiis corporis ullam totam. Tenetur exercitationem tenetur et et quas.\n\nEt dignissimos esse magnam et. Omnis nulla et molestias ducimus doloremque recusandae iste.",
    "room": {
      "id": 3,
      "name": "Modular Conference Room",
      "number": "H-711-4",
      "floor": 7,
      "building": "Hall",
      "created_at": "2021-03-28T17:52:29.000000Z",
      "updated_at": "2021-03-28T17:52:29.000000Z",
      "status": "available",
      "min_days_advance": 0,
      "max_days_advance": 200,
      "attributes": {
        "food": false,
        "sofas": 0,
        "chairs": 10,
        "tables": 1,
        "alcohol": false,
        "computer": false,
        "projector": true,
        "fundraiser": false,
        "television": false,
        "whiteboard": false,
        "a_v_permitted": true,
        "ambiant_music": false,
        "coffee_tables": 0,
        "sale_for_profit": false,
        "capacity_sitting": "20",
        "capacity_standing": "0"
      },
      "room_type": "Conference",
      "pivot": {
        "booking_request_id": 10,
        "room_id": 3,
        "created_at": "2021-03-28T17:52:30.000000Z",
        "updated_at": "2021-03-28T17:52:30.000000Z"
      }
    },
    "requester": {
      "id": 1,
      "name": "Joseph doe",
      "email": "admin@email.com",
      "email_verified_at": "2021-03-28T17:52:28.000000Z",
      "current_team_id": null,
      "profile_photo_path": null,
      "created_at": "2021-03-28T17:52:28.000000Z",
      "updated_at": "2021-03-28T17:52:28.000000Z",
      "phone": "341-262-3829",
      "organization": "Batz-Heidenreich",
      "profile_photo_url": "https://ui-avatars.com/api/?name=Joseph+doe&color=7F9CF5&background=EBF4FF",
      "can": ["users.select", "users.select.same-role", "users.create", "users.update", "users.delete", "roles.assign", "roles.create", "roles.update", "roles.delete", "bookings.create", "bookings.update", "bookings.approve", "bookings.delete", "rooms.create", "rooms.update", "rooms.delete", "rooms.blackouts.create", "rooms.blackouts.update", "rooms.blackouts.delete", "settings.edit"],
      "permissions": [],
      "roles": [{
        "id": 1,
        "name": "super-admin",
        "guard_name": "web",
        "created_at": "2021-03-28T17:52:28.000000Z",
        "updated_at": "2021-03-28T17:52:28.000000Z",
        "number_of_bookings_per_period": null,
        "number_of_days_per_period": null,
        "pivot": {"model_id": 1, "role_id": 1, "model_type": "App\\Models\\User"},
        "permissions": [{
          "id": 1,
          "name": "users.select",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 1}
        }, {
          "id": 2,
          "name": "users.select.same-role",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 2}
        }, {
          "id": 3,
          "name": "users.create",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 3}
        }, {
          "id": 4,
          "name": "users.update",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 4}
        }, {
          "id": 5,
          "name": "users.delete",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 5}
        }, {
          "id": 6,
          "name": "roles.assign",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 6}
        }, {
          "id": 7,
          "name": "roles.create",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 7}
        }, {
          "id": 8,
          "name": "roles.update",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 8}
        }, {
          "id": 9,
          "name": "roles.delete",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 9}
        }, {
          "id": 10,
          "name": "bookings.create",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 10}
        }, {
          "id": 11,
          "name": "bookings.update",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 11}
        }, {
          "id": 12,
          "name": "bookings.approve",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:27.000000Z",
          "updated_at": "2021-03-28T17:52:27.000000Z",
          "pivot": {"role_id": 1, "permission_id": 12}
        }, {
          "id": 13,
          "name": "bookings.delete",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 13}
        }, {
          "id": 14,
          "name": "rooms.create",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 14}
        }, {
          "id": 15,
          "name": "rooms.update",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 15}
        }, {
          "id": 16,
          "name": "rooms.delete",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 16}
        }, {
          "id": 17,
          "name": "rooms.blackouts.create",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 17}
        }, {
          "id": 18,
          "name": "rooms.blackouts.update",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 18}
        }, {
          "id": 19,
          "name": "rooms.blackouts.delete",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 19}
        }, {
          "id": 20,
          "name": "settings.edit",
          "guard_name": "web",
          "created_at": "2021-03-28T17:52:28.000000Z",
          "updated_at": "2021-03-28T17:52:28.000000Z",
          "pivot": {"role_id": 1, "permission_id": 20}
        }]
      }]
    },
    "reservations": [{
      "id": 10,
      "room_id": 3,
      "booking_request_id": 10,
      "start_time": "2021-04-20T17:53:57.000000Z",
      "end_time": "2021-04-20T19:53:57.000000Z",
      "created_at": "2021-03-28T17:52:30.000000Z",
      "updated_at": "2021-03-28T17:52:30.000000Z",
      "room": {
        "id": 3,
        "name": "Modular Conference Room",
        "number": "H-711-4",
        "floor": 7,
        "building": "Hall",
        "created_at": "2021-03-28T17:52:29.000000Z",
        "updated_at": "2021-03-28T17:52:29.000000Z",
        "status": "available",
        "min_days_advance": 0,
        "max_days_advance": 200,
        "attributes": {
          "food": false,
          "sofas": 0,
          "chairs": 10,
          "tables": 1,
          "alcohol": false,
          "computer": false,
          "projector": true,
          "fundraiser": false,
          "television": false,
          "whiteboard": false,
          "a_v_permitted": true,
          "ambiant_music": false,
          "coffee_tables": 0,
          "sale_for_profit": false,
          "capacity_sitting": "20",
          "capacity_standing": "0"
        },
        "room_type": "Conference"
      }
    }]
  }
];

export let mocked =  {
  "id": 5,
  "user_id": 1,
  "status": "approved",
  "created_at": "2021-03-28T17:52:29.000000Z",
  "updated_at": "2021-03-28T17:52:29.000000Z",
  "reference": [],
  "log": null,
  "onsite_contact": {
    "name": "Gilberto Kuvalis",
    "email": "montana.jacobson@yahoo.com",
    "phone": "(438) 315-9854 x8903"
  },
  "event": {
    "type": "praesentium",
    "title": "et",
    "attendees": 296670193,
    "description": "Et voluptatibus dolor nostrum et voluptatem. Nihil et eum maxime aut qui sint. Explicabo ipsum dolorem occaecati aut fugiat.",
    "guest_speakers": "Favian Wyman"
  },
  "notes": "Reprehenderit corrupti pariatur ut occaecati rem. Est itaque iusto non vero. Officiis corrupti eligendi nihil voluptatem.\n\nQuis quia doloremque magnam omnis. Voluptas reiciendis quam architecto ea libero aut doloribus. Animi libero inventore ducimus et. Amet iste excepturi aut.\n\nQuis cumque cum odit rem maiores. Velit repellendus nihil ratione eaque aliquam in.",
  "room": {
    "id": 1,
    "name": "CSU Lounge",
    "number": "H-709",
    "floor": 7,
    "building": "Hall",
    "created_at": "2021-03-28T17:52:28.000000Z",
    "updated_at": "2021-03-28T17:52:28.000000Z",
    "status": "available",
    "min_days_advance": 10,
    "max_days_advance": 25,
    "attributes": {
      "food": true,
      "sofas": 3,
      "chairs": 0,
      "tables": 0,
      "alcohol": true,
      "computer": false,
      "projector": false,
      "fundraiser": false,
      "television": false,
      "whiteboard": false,
      "a_v_permitted": true,
      "ambiant_music": false,
      "coffee_tables": 3,
      "sale_for_profit": false,
      "capacity_sitting": "150",
      "capacity_standing": "450"
    },
    "room_type": "Lounge",
    "pivot": {
      "booking_request_id": 5,
      "room_id": 1,
      "created_at": "2021-03-28T17:52:29.000000Z",
      "updated_at": "2021-03-28T17:52:29.000000Z"
    }
  },
  "requester": {
    "id": 1,
    "name": "Joseph doe",
    "email": "admin@email.com",
    "email_verified_at": "2021-03-28T17:52:28.000000Z",
    "current_team_id": null,
    "profile_photo_path": null,
    "created_at": "2021-03-28T17:52:28.000000Z",
    "updated_at": "2021-03-28T17:52:28.000000Z",
    "phone": "341-262-3829",
    "organization": "Batz-Heidenreich",
    "profile_photo_url": "https://ui-avatars.com/api/?name=Joseph+doe&color=7F9CF5&background=EBF4FF",
    "can": ["users.select", "users.select.same-role", "users.create", "users.update", "users.delete", "roles.assign", "roles.create", "roles.update", "roles.delete", "bookings.create", "bookings.update", "bookings.approve", "bookings.delete", "rooms.create", "rooms.update", "rooms.delete", "rooms.blackouts.create", "rooms.blackouts.update", "rooms.blackouts.delete", "settings.edit"],
    "permissions": [],
    "roles": [{
      "id": 1,
      "name": "super-admin",
      "guard_name": "web",
      "created_at": "2021-03-28T17:52:28.000000Z",
      "updated_at": "2021-03-28T17:52:28.000000Z",
      "number_of_bookings_per_period": null,
      "number_of_days_per_period": null,
      "pivot": {"model_id": 1, "role_id": 1, "model_type": "App\\Models\\User"},
      "permissions": [{
        "id": 1,
        "name": "users.select",
        "guard_name": "web",
        "created_at": "2021-03-28T17:52:27.000000Z",
        "updated_at": "2021-03-28T17:52:27.000000Z",
        "pivot": {"role_id": 1, "permission_id": 1}
      }, {
        "id": 2,
        "name": "users.select.same-role",
        "guard_name": "web",
        "created_at": "2021-03-28T17:52:27.000000Z",
        "updated_at": "2021-03-28T17:52:27.000000Z",
        "pivot": {"role_id": 1, "permission_id": 2}
      }, {
        "id": 3,
        "name": "users.create",
        "guard_name": "web",
        "created_at": "2021-03-28T17:52:27.000000Z",
        "updated_at": "2021-03-28T17:52:27.000000Z",
        "pivot": {"role_id": 1, "permission_id": 3}
      }, {
        "id": 4,
        "name": "users.update",
        "guard_name": "web",
        "created_at": "2021-03-28T17:52:27.000000Z",
        "updated_at": "2021-03-28T17:52:27.000000Z",
        "pivot": {"role_id": 1, "permission_id": 4}
      }, {
        "id": 5,
        "name": "users.delete",
        "guard_name": "web",
        "created_at": "2021-03-28T17:52:27.000000Z",
        "updated_at": "2021-03-28T17:52:27.000000Z",
        "pivot": {"role_id": 1, "permission_id": 5}
      }, {
        "id": 6,
        "name": "roles.assign",
        "guard_name": "web",
        "created_at": "2021-03-28T17:52:27.000000Z",
        "updated_at": "2021-03-28T17:52:27.000000Z",
        "pivot": {"role_id": 1, "permission_id": 6}
      }, {
        "id": 7,
        "name": "roles.create",
        "guard_name": "web",
        "created_at": "2021-03-28T17:52:27.000000Z",
        "updated_at": "2021-03-28T17:52:27.000000Z",
        "pivot": {"role_id": 1, "permission_id": 7}
      }, {
        "id": 8,
        "name": "roles.update",
        "guard_name": "web",
        "created_at": "2021-03-28T17:52:27.000000Z",
        "updated_at": "2021-03-28T17:52:27.000000Z",
        "pivot": {"role_id": 1, "permission_id": 8}
      }, {
        "id": 9,
        "name": "roles.delete",
        "guard_name": "web",
        "created_at": "2021-03-28T17:52:27.000000Z",
        "updated_at": "2021-03-28T17:52:27.000000Z",
        "pivot": {"role_id": 1, "permission_id": 9}
      }, {
        "id": 10,
        "name": "bookings.create",
        "guard_name": "web",
        "created_at": "2021-03-28T17:52:27.000000Z",
        "updated_at": "2021-03-28T17:52:27.000000Z",
        "pivot": {"role_id": 1, "permission_id": 10}
      }, {
        "id": 11,
        "name": "bookings.update",
        "guard_name": "web",
        "created_at": "2021-03-28T17:52:27.000000Z",
        "updated_at": "2021-03-28T17:52:27.000000Z",
        "pivot": {"role_id": 1, "permission_id": 11}
      }, {
        "id": 12,
        "name": "bookings.approve",
        "guard_name": "web",
        "created_at": "2021-03-28T17:52:27.000000Z",
        "updated_at": "2021-03-28T17:52:27.000000Z",
        "pivot": {"role_id": 1, "permission_id": 12}
      }, {
        "id": 13,
        "name": "bookings.delete",
        "guard_name": "web",
        "created_at": "2021-03-28T17:52:28.000000Z",
        "updated_at": "2021-03-28T17:52:28.000000Z",
        "pivot": {"role_id": 1, "permission_id": 13}
      }, {
        "id": 14,
        "name": "rooms.create",
        "guard_name": "web",
        "created_at": "2021-03-28T17:52:28.000000Z",
        "updated_at": "2021-03-28T17:52:28.000000Z",
        "pivot": {"role_id": 1, "permission_id": 14}
      }, {
        "id": 15,
        "name": "rooms.update",
        "guard_name": "web",
        "created_at": "2021-03-28T17:52:28.000000Z",
        "updated_at": "2021-03-28T17:52:28.000000Z",
        "pivot": {"role_id": 1, "permission_id": 15}
      }, {
        "id": 16,
        "name": "rooms.delete",
        "guard_name": "web",
        "created_at": "2021-03-28T17:52:28.000000Z",
        "updated_at": "2021-03-28T17:52:28.000000Z",
        "pivot": {"role_id": 1, "permission_id": 16}
      }, {
        "id": 17,
        "name": "rooms.blackouts.create",
        "guard_name": "web",
        "created_at": "2021-03-28T17:52:28.000000Z",
        "updated_at": "2021-03-28T17:52:28.000000Z",
        "pivot": {"role_id": 1, "permission_id": 17}
      }, {
        "id": 18,
        "name": "rooms.blackouts.update",
        "guard_name": "web",
        "created_at": "2021-03-28T17:52:28.000000Z",
        "updated_at": "2021-03-28T17:52:28.000000Z",
        "pivot": {"role_id": 1, "permission_id": 18}
      }, {
        "id": 19,
        "name": "rooms.blackouts.delete",
        "guard_name": "web",
        "created_at": "2021-03-28T17:52:28.000000Z",
        "updated_at": "2021-03-28T17:52:28.000000Z",
        "pivot": {"role_id": 1, "permission_id": 19}
      }, {
        "id": 20,
        "name": "settings.edit",
        "guard_name": "web",
        "created_at": "2021-03-28T17:52:28.000000Z",
        "updated_at": "2021-03-28T17:52:28.000000Z",
        "pivot": {"role_id": 1, "permission_id": 20}
      }]
    }]
  },
  "reservations": [{
    "id": 5,
    "room_id": 1,
    "booking_request_id": 5,
    "start_time": "2021-04-01T12:46:00.000000Z",
    "end_time": "2021-04-01T14:46:00.000000Z",
    "created_at": "2021-03-28T17:52:29.000000Z",
    "updated_at": "2021-03-28T17:52:29.000000Z",
    "room": {
      "id": 1,
      "name": "CSU Lounge",
      "number": "H-709",
      "floor": 7,
      "building": "Hall",
      "created_at": "2021-03-28T17:52:28.000000Z",
      "updated_at": "2021-03-28T17:52:28.000000Z",
      "status": "available",
      "min_days_advance": 10,
      "max_days_advance": 25,
      "attributes": {
        "food": true,
        "sofas": 3,
        "chairs": 0,
        "tables": 0,
        "alcohol": true,
        "computer": false,
        "projector": false,
        "fundraiser": false,
        "television": false,
        "whiteboard": false,
        "a_v_permitted": true,
        "ambiant_music": false,
        "coffee_tables": 3,
        "sale_for_profit": false,
        "capacity_sitting": "150",
        "capacity_standing": "450"
      },
      "room_type": "Lounge"
    }
  }]
}


