import React, {Component} from "react";
import {AgGridReact} from "ag-grid-react";
import {Router, Route, IndexRoute, browserHistory} from 'react-router';

export default class extends Component {
    constructor(props) {
        super(props);

        this.state = {
            columnDefs: this.createColumnDefs(),
            rowData: [],
            rowGroupPanelShow: "always",
            fileName: ''
        }
    }

    componentDidMount() {
        fetch('/api/aa')
            .then(response => {
                return response.json();
            })
            .then(fakedb => {
                var temp = this.createRowData(fakedb);
                this.setState({rowData: temp});
            })
    }

    fieldChanged(field, event) {
        this.setState({[field]: event.target.value});
    }

    saveFilterModel() {
        var temp = this.gridApi.getFilterModel();
        var tempjson = {"savedName" : this.state.fileName};
        if(temp.sex != undefined)
            tempjson.sex = temp.sex.toString();
        else
            tempjson.sex = null;
        if(temp.country != undefined)
            tempjson.country = temp.country.toString();
        else
            tempjson.country = null;
        if(temp.profession != undefined)
            tempjson.profession = temp.profession.toString();
        else
            tempjson.profession = null;
        if(temp.proLevel != undefined)
            tempjson.proLevel = temp.proLevel.toString();
        else
            tempjson.proLevel = null;

        temp.saved = tempjson;
        if(this.gridApi.getSortModel()[0] != undefined)
            temp.sorting = this.gridApi.getSortModel()[0];
        console.log(temp);
        fetch('/api/filterSaving', {
            method: 'POST',
            body: JSON.stringify(temp)
        })
            .then(response => {
                return response.json();
            })
            .then(filters => {
                console.log(filters);
            /*var temp = this.createRowData(fakedb);
            this.setState({rowData: temp});*/
        });
    }
    loadFilterModel(){
        /*fetch('/api/aa')
            .then(response => {
                return response.json();
            })
            .then(fakedb => {
                var temp = this.createRowData(fakedb);
                this.setState({rowData: temp});
            })*/
        this.gridApi.setFilterModel({"adress":{"type": "contains", "filter": "4", "filterType": "text"},
            "birthday":{"dateTo": null, "dateFrom": "2017-11-27", "type": "lessThan", "filterType": "date"},
            "email":{"type": "contains", "filter": ".com", "filterType": "text"},
            "name":{"type": "contains", "filter": "a", "filterType": "text"},
            "phoneNumber":{"type": "contains", "filter": "40", "filterType": "text"},
            "salary":{"type": "greaterThan", "filter": 5, "filterTo": null, "filterType": "number"},
            "sex":["female"]
        });
        this.gridApi.onFilterChanged();
        //{"name":{"type":"contains","filter":"a","filterType":"text"},"sex":["female"],"birthday":{"dateTo":"1999-12-14","dateFrom":"1998-06-16","type":"inRange","filterType":"date"}}
    }

    onGridReady(params) {
        this.gridApi = params.api;
        this.columnApi = params.columnApi;
        this.gridApi.sizeColumnsToFit();
    }

    autoSizeAll() {
        var allColumnIds = [];
        this.columnApi.getAllColumns().forEach(function(column) {
            allColumnIds.push(column.colId);
        });
        this.columnApi.autoSizeColumns(allColumnIds);
    }

    createColumnDefs() {
        return [
            {
                headerName: "Name", field: "name", filter: "text",
                filterParams: {
                    filterOptions: ["contains", "notContains"]
                }
            },
            {
                headerName: "Sex", field: "sex", filter: "set",
                filterParams: { selectAllOnMiniFilter: true }

            },
            {
                headerName: "Birthday", field: "birthday", filter: "date",
                filterParams: {
                    comparator: function(filterLocalDateAtMidnight, cellValue) {
                        var dateAsString = cellValue;
                        var dateParts = dateAsString.split("-");
                        var cellDate = new Date(Number(dateParts[0]), Number(dateParts[1]) - 1, Number(dateParts[2]));
                        if (filterLocalDateAtMidnight.getTime() == cellDate.getTime()) {
                            return 0;
                        }
                        if (cellDate < filterLocalDateAtMidnight) {
                            return -1;
                        }
                        if (cellDate > filterLocalDateAtMidnight) {
                            return 1;
                        }
                    }
                }
            },
            {
                headerName: "Phone number", field: "phoneNumber", filter: "text",
                filterParams: {
                    filterOptions: ["contains", "notContains"]
                }
            },
            {
                headerName: "Adress", field: "adress", filter: "text",
                filterParams: {
                    filterOptions: ["contains", "notContains"]
                }
            },
            {
                headerName: "Country", field: "country", filter: "set",
                filterParams: { selectAllOnMiniFilter: true }
            },
            {
                headerName: "Email", field: "email", filter: "text",
                filterParams: {
                    filterOptions: ["contains", "notContains"]
                }
            },
            {
                headerName: "Salary", field: "salary", filter: "number"
            },
            {
                headerName: "Profession", field: "profession", filter: "set",
                filterParams: { selectAllOnMiniFilter: true }
            },
            {
                headerName: "Pro Level", field: "proLevel", filter: "set",
                filterParams: { selectAllOnMiniFilter: true }
            },
        ];
    }

    createRowData(fakedb) {
        var temp = [];
        temp.push(fakedb.map(rows => {
                return(
                    {adress: rows["Adress"], birthday:rows["Birthday"], country: rows["Country"], email:rows["Email"],
                        name:rows["Name"], phoneNumber:rows["Phone number"], profession:rows["Profession"],
                        proLevel:rows["Professional Level (1-5)"], salary:rows["Salary"], sex:rows["Sex"]}
                );
            })
        );
        return temp[0];
    }
    onFilterChanged(value) {
        this.gridApi.setQuickFilter(value);
    }

    render() {
        let containerStyle = {
            height: "100%",
            width: "100%",
        };

        return (
            <div className="container">
                <div style={containerStyle} className="ag-fresh">
                    <label>
                        File Name:
                        <input type="text" id="fileName" value={this.state.fileName}
                               onChange={(event) => this.fieldChanged('fileName', event)}/>
                    </label>
                    <button onClick={this.saveFilterModel.bind(this)}>Save Filter Model</button>
                    <button onClick={this.loadFilterModel.bind(this)}>Load Filter Model</button>
                    <AgGridReact
                        // properties
                        columnDefs={this.state.columnDefs}
                        rowData={this.state.rowData}
                        enableColResize={true}
                        enableFilter={true}
                        enableSorting={true}
                        floatingFilter={true}
                        showToolPanel={true}

                        // events
                        onGridReady={this.onGridReady.bind(this)}
                        onFilterChanged={this.onFilterChanged.bind(this)}>

                    </AgGridReact>
                    <button onClick={this.autoSizeAll.bind(this)}>Auto-Size All</button>
                </div>
            </div>
        )
    }
};
