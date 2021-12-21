export class NewTask {
    id?: string = "0";
    type?: string = "activity";
    name?: string = "";
    description?: string = "";
    notes?: string = "";
    tags?: string = "";
    prio?: string = "strict";
    status?: string = "started";
    duedate?: any = new Date();
}